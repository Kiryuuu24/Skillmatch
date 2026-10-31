<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Profile::create([
            'user_id' => $this->user->id,
            'is_open_to_join' => true,
            'is_portfolio_public' => true,
            'is_sosmed_public' => true,
        ]);
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /**
     * Test creating a post with valid data (KF-04 AC).
     */
    public function test_user_can_create_post(): void
    {
        $skill = Skill::create([
            'nama_skill' => 'Laravel',
            'kategori_skill' => 'Pemrograman',
        ]);

        $response = $this->withToken($this->token)
            ->postJson('/api/posts', [
                'nama_lomba' => 'Hackathon Unila 2026',
                'kategori_lomba' => 'Pemrograman',
                'jumlah_dibutuhkan' => 3,
                'deadline_daftar' => now()->addDays(30)->toDateString(),
                'link_sosmed' => 'https://instagram.com/tim_hackathon',
                'deskripsi_syarat' => 'Mahasiswa aktif TI Unila',
                'skills' => [
                    ['skill_id' => $skill->id, 'prioritas' => 'wajib'],
                ],
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Postingan berhasil dibuat dan dipublikasikan',
            ]);

        $this->assertDatabaseHas('posts', [
            'nama_lomba' => 'Hackathon Unila 2026',
            'ketua_id' => $this->user->id,
            'status_postingan' => 'aktif',
        ]);
    }

    /**
     * Test creating a post without required fields fails (KF-04 AC).
     */
    public function test_create_post_fails_without_required_fields(): void
    {
        $response = $this->withToken($this->token)
            ->postJson('/api/posts', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'nama_lomba',
                'kategori_lomba',
                'jumlah_dibutuhkan',
                'deadline_daftar',
                'link_sosmed',
                'skills',
            ]);
    }

    /**
     * Test listing posts (KF-06 AC).
     */
    public function test_user_can_list_posts(): void
    {
        $skill = Skill::create([
            'nama_skill' => 'UI/UX',
            'kategori_skill' => 'Desain',
        ]);

        $post = Post::create([
            'ketua_id' => $this->user->id,
            'nama_lomba' => 'UI/UX Competition',
            'kategori_lomba' => 'Desain',
            'jumlah_dibutuhkan' => 2,
            'jumlah_terisi' => 0,
            'deadline_daftar' => now()->addDays(14),
            'link_sosmed' => 'https://wa.me/1234',
            'status_postingan' => 'aktif',
        ]);

        $response = $this->withToken($this->token)->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test post detail (KF-06 AC).
     */
    public function test_user_can_view_post_detail(): void
    {
        $post = Post::create([
            'ketua_id' => $this->user->id,
            'nama_lomba' => 'CTF Challenge',
            'kategori_lomba' => 'Keamanan',
            'jumlah_dibutuhkan' => 3,
            'jumlah_terisi' => 1,
            'deadline_daftar' => now()->addDays(7),
            'link_sosmed' => 'https://wa.me/5678',
            'status_postingan' => 'aktif',
        ]);

        $response = $this->withToken($this->token)->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.post.nama_lomba', 'CTF Challenge');
    }

    /**
     * Test searching posts with keyword (KF-06 AC).
     */
    public function test_can_search_posts_by_keyword(): void
    {
        Post::create([
            'ketua_id' => $this->user->id,
            'nama_lomba' => 'Data Mining Competition',
            'kategori_lomba' => 'Data Science',
            'jumlah_dibutuhkan' => 2,
            'jumlah_terisi' => 0,
            'deadline_daftar' => now()->addDays(30),
            'link_sosmed' => 'https://wa.me/9999',
            'status_postingan' => 'aktif',
        ]);

        Post::create([
            'ketua_id' => $this->user->id,
            'nama_lomba' => 'Hackathon',
            'kategori_lomba' => 'Pemrograman',
            'jumlah_dibutuhkan' => 4,
            'jumlah_terisi' => 0,
            'deadline_daftar' => now()->addDays(30),
            'link_sosmed' => 'https://wa.me/8888',
            'status_postingan' => 'aktif',
        ]);

        $response = $this->withToken($this->token)
            ->getJson('/api/posts?keyword=Data Mining');

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, count($response->json('data.data')));
    }

    /**
     * Test hidden posts are not visible (KF-11 AC).
     */
    public function test_hidden_posts_not_visible_in_listing(): void
    {
        Post::create([
            'ketua_id' => $this->user->id,
            'nama_lomba' => 'Hidden Post',
            'kategori_lomba' => 'Test',
            'jumlah_dibutuhkan' => 2,
            'jumlah_terisi' => 0,
            'deadline_daftar' => now()->addDays(30),
            'link_sosmed' => 'https://wa.me/1111',
            'status_postingan' => 'disembunyikan',
        ]);

        $response = $this->withToken($this->token)->getJson('/api/posts');

        $response->assertStatus(200);
        foreach ($response->json('data.data') as $post) {
            $this->assertNotEquals('Hidden Post', $post['nama_lomba']);
        }
    }

    /**
     * Test searching candidates (KF-05 AC).
     */
    public function test_can_search_candidates(): void
    {
        $candidate = User::factory()->create(['name' => 'John Programmer']);
        Profile::create([
            'user_id' => $candidate->id,
            'prodi' => 'Teknik Informatika',
            'is_open_to_join' => true,
            'is_portfolio_public' => true,
            'is_sosmed_public' => true,
        ]);

        $response = $this->withToken($this->token)
            ->getJson('/api/posts/search-candidates?open_to_join=1');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
