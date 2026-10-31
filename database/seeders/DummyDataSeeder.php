<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Post;
use App\Models\PostSkillReq;
use App\Models\Application;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 dummy users
        $users = [];
        $names = [
            'Rizki Pratama', 'Siti Nurhaliza', 'Budi Santoso', 'Dewi Lestari', 'Ahmad Fauzi',
            'Putri Wijaya', 'Andi Setiawan', 'Maya Safitri', 'Fajar Ramadhan', 'Lina Marlina'
        ];

        $prodis = [
            'Teknik Informatika', 'Sistem Informasi', 'Teknik Komputer', 'Teknik Elektro'
        ];

        foreach ($names as $index => $name) {
            $username = strtolower(str_replace(' ', '', $name));
            
            // Skip if user already exists
            if (User::where('username', $username)->exists()) {
                $users[] = User::where('username', $username)->first();
                continue;
            }
            
            $user = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $username . '@unila.ac.id',
                'password' => Hash::make('password123'),
            ]);

            Profile::create([
                'user_id' => $user->id,
                'prodi' => $prodis[array_rand($prodis)],
                'angkatan' => rand(2020, 2024),
                'npm' => '2' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
                'is_open_to_join' => rand(0, 1),
                'link_portofolio' => rand(0, 1) ? 'https://github.com/' . $username : null,
            ]);

            // Assign random skills to user via UserSkill model
            $skills = Skill::inRandomOrder()->limit(rand(3, 7))->get();
            foreach ($skills as $skill) {
                \App\Models\UserSkill::create([
                    'user_id' => $user->id,
                    'skill_id' => $skill->id,
                    'tingkat_keahlian' => ['pemula', 'menengah', 'mahir'][rand(0, 2)],
                ]);
            }

            $users[] = $user;
        }

        // Create 25 posts (lebih banyak untuk testing)
        $lombas = [
            'GEMASTIK 2026 - Web Development',
            'Hackathon Unila 2026',
            'LIDM 2026 - Data Mining',
            'Kompetisi IoT Nasional',
            'UI/UX Design Competition',
            'Mobile App Development Contest',
            'Cyber Security Challenge',
            'PKM 2026 - Karsa Cipta',
            'Business Plan Competition',
            'Game Development Jam',
            'Network Configuration Challenge',
            'AI Innovation Contest',
            'KMIPN 2026 - Software Engineering',
            'Digital Marketing Challenge',
            'Robotics Competition',
            'Smart City Innovation',
            'E-Commerce Platform Challenge',
            'Blockchain Development Contest',
            'Cloud Computing Competition',
            'Machine Learning Olympiad',
            'Startup Pitch Competition',
            'Video Game Design Jam',
            'AR/VR Development Challenge',
            'Database Design Competition',
            'API Development Contest'
        ];

        $kategoris = [
            'Pemrograman', 'Desain', 'Data & AI', 'Hardware & IoT', 
            'Bisnis & Manajemen', 'Keamanan', 'Jaringan'
        ];

        $syarat = [
            'Mahasiswa aktif Teknik Informatika atau prodi terkait, berpengalaman di bidang terkait, komitmen tinggi',
            'Mahasiswa S1 aktif, punya portofolio, siap kerja tim',
            'Minimal semester 3, pernah ikut lomba sebelumnya (nilai plus)',
            'Bersedia commit full time selama persiapan, mahasiswa Unila',
            'Punya laptop sendiri, domisili Lampung, siap meeting offline'
        ];

        foreach ($lombas as $index => $lomba) {
            $ketua = $users[array_rand($users)];
            $jumlahDibutuhkan = rand(2, 5);
            $jumlahTerisi = rand(0, $jumlahDibutuhkan - 1);
            
            $deadline = now()->addDays(rand(3, 30));
            
            $post = Post::create([
                'ketua_id' => $ketua->id,
                'nama_lomba' => $lomba,
                'kategori_lomba' => $kategoris[array_rand($kategoris)],
                'jumlah_dibutuhkan' => $jumlahDibutuhkan,
                'jumlah_terisi' => $jumlahTerisi,
                'deadline_daftar' => $deadline,
                'link_sosmed' => 'https://wa.me/62812345678' . $index,
                'deskripsi_syarat' => $syarat[array_rand($syarat)],
                'status_postingan' => $jumlahTerisi < $jumlahDibutuhkan ? 'aktif' : 'penuh',
            ]);

            // Add skill requirements (2-4 skills per post)
            $postSkills = Skill::inRandomOrder()->limit(rand(2, 4))->get();
            foreach ($postSkills as $skill) {
                PostSkillReq::create([
                    'post_id' => $post->id,
                    'skill_id' => $skill->id,
                    'prioritas' => rand(0, 1) ? 'wajib' : 'opsional',
                ]);
            }
        }

        // Create applications (lebih banyak untuk testing)
        $allPosts = Post::where('status_postingan', 'aktif')->get();
        $statuses = ['menunggu', 'diterima', 'ditolak'];

        foreach ($allPosts as $post) {
            // Random 3-8 applications per post (lebih banyak)
            $numApplicants = min(rand(3, 8), count($users) - 1);
            $applicants = collect($users)
                ->filter(fn($u) => $u->id !== $post->ketua_id)
                ->random($numApplicants);

            foreach ($applicants as $applicant) {
                Application::create([
                    'post_id' => $post->id,
                    'pelamar_id' => $applicant->id,
                    'status_pengajuan' => $statuses[array_rand($statuses)],
                    'pesan_lamaran' => rand(0, 1) ? 'Saya tertarik bergabung dengan tim ini. Saya memiliki pengalaman di bidang terkait dan siap berkontribusi maksimal.' : null,
                ]);
            }
        }

        $this->command->info('✅ Dummy data created successfully!');
        $this->command->info('📊 Created: ' . count($users) . ' users, ' . count($lombas) . ' posts');
    }
}
