<?php

namespace Tests\Feature\Api;

use App\Models\Application;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    private User $ketua;

    private User $pelamar;

    private Post $post;

    private string $ketuaToken;

    private string $pelamarToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ketua = User::factory()->create();
        Profile::create([
            'user_id' => $this->ketua->id,
            'is_open_to_join' => false,
            'is_portfolio_public' => true,
            'is_sosmed_public' => true,
        ]);
        $this->ketuaToken = $this->ketua->createToken('test')->plainTextToken;

        $this->pelamar = User::factory()->create();
        Profile::create([
            'user_id' => $this->pelamar->id,
            'is_open_to_join' => true,
            'is_portfolio_public' => true,
            'is_sosmed_public' => true,
        ]);
        $this->pelamarToken = $this->pelamar->createToken('test')->plainTextToken;

        $this->post = Post::create([
            'ketua_id' => $this->ketua->id,
            'nama_lomba' => 'Test Lomba',
            'kategori_lomba' => 'Pemrograman',
            'jumlah_dibutuhkan' => 3,
            'jumlah_terisi' => 0,
            'deadline_daftar' => now()->addDays(30),
            'link_sosmed' => 'https://wa.me/1234',
            'status_postingan' => 'aktif',
        ]);
    }

    /**
     * Test pelamar can apply to a post (KF-07 AC).
     */
    public function test_pelamar_can_apply_to_post(): void
    {
        $response = $this->withToken($this->pelamarToken)
            ->postJson('/api/applications', [
                'post_id' => $this->post->id,
                'pesan_lamaran' => 'Saya tertarik bergabung.',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim',
            ]);

        $this->assertDatabaseHas('applications', [
            'post_id' => $this->post->id,
            'pelamar_id' => $this->pelamar->id,
            'status_pengajuan' => 'menunggu',
        ]);
    }

    /**
     * Test pelamar cannot apply to own post (KF-07 AC).
     */
    public function test_pelamar_cannot_apply_to_own_post(): void
    {
        $response = $this->withToken($this->ketuaToken)
            ->postJson('/api/applications', [
                'post_id' => $this->post->id,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Anda tidak dapat mendaftar ke postingan sendiri',
            ]);
    }

    /**
     * Test pelamar cannot apply twice to same post (KF-07 AC).
     */
    public function test_pelamar_cannot_apply_twice(): void
    {
        Application::create([
            'post_id' => $this->post->id,
            'pelamar_id' => $this->pelamar->id,
            'status_pengajuan' => 'menunggu',
        ]);

        $response = $this->withToken($this->pelamarToken)
            ->postJson('/api/applications', [
                'post_id' => $this->post->id,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Anda sudah mendaftar ke postingan ini',
            ]);
    }

    /**
     * Test cannot apply to full post (KF-07 AC).
     */
    public function test_cannot_apply_to_full_post(): void
    {
        $this->post->update([
            'jumlah_terisi' => 3,
            'status_postingan' => 'penuh',
        ]);

        $response = $this->withToken($this->pelamarToken)
            ->postJson('/api/applications', [
                'post_id' => $this->post->id,
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test ketua can accept application (KF-08 AC).
     */
    public function test_ketua_can_accept_application(): void
    {
        $application = Application::create([
            'post_id' => $this->post->id,
            'pelamar_id' => $this->pelamar->id,
            'status_pengajuan' => 'menunggu',
        ]);

        $response = $this->withToken($this->ketuaToken)
            ->putJson("/api/applications/{$application->id}", [
                'status_pengajuan' => 'diterima',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Pengajuan berhasil diterima',
            ]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status_pengajuan' => 'diterima',
        ]);

        // jumlah_terisi should increase
        $this->assertDatabaseHas('posts', [
            'id' => $this->post->id,
            'jumlah_terisi' => 1,
        ]);
    }

    /**
     * Test ketua can reject application (KF-08 AC).
     */
    public function test_ketua_can_reject_application(): void
    {
        $application = Application::create([
            'post_id' => $this->post->id,
            'pelamar_id' => $this->pelamar->id,
            'status_pengajuan' => 'menunggu',
        ]);

        $response = $this->withToken($this->ketuaToken)
            ->putJson("/api/applications/{$application->id}", [
                'status_pengajuan' => 'ditolak',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Pengajuan berhasil ditolak',
            ]);
    }

    /**
     * Test post becomes full when quota met (KF-08 AC).
     */
    public function test_post_becomes_full_when_quota_met(): void
    {
        $this->post->update(['jumlah_dibutuhkan' => 1]);

        $application = Application::create([
            'post_id' => $this->post->id,
            'pelamar_id' => $this->pelamar->id,
            'status_pengajuan' => 'menunggu',
        ]);

        $this->withToken($this->ketuaToken)
            ->putJson("/api/applications/{$application->id}", [
                'status_pengajuan' => 'diterima',
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => $this->post->id,
            'status_postingan' => 'penuh',
        ]);
    }

    /**
     * Test non-ketua cannot update application (KF-08 AC).
     */
    public function test_non_ketua_cannot_update_application(): void
    {
        $application = Application::create([
            'post_id' => $this->post->id,
            'pelamar_id' => $this->pelamar->id,
            'status_pengajuan' => 'menunggu',
        ]);

        $otherUser = User::factory()->create();
        $otherToken = $otherUser->createToken('test')->plainTextToken;

        $response = $this->withToken($otherToken)
            ->putJson("/api/applications/{$application->id}", [
                'status_pengajuan' => 'diterima',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test pelamar can view own applications.
     */
    public function test_pelamar_can_view_own_applications(): void
    {
        Application::create([
            'post_id' => $this->post->id,
            'pelamar_id' => $this->pelamar->id,
            'status_pengajuan' => 'menunggu',
        ]);

        $response = $this->withToken($this->pelamarToken)
            ->getJson('/api/applications?role=pelamar&tab=menunggu');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
