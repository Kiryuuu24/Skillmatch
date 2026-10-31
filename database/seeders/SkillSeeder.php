<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Pemrograman
            ['nama_skill' => 'Laravel', 'kategori_skill' => 'Pemrograman'],
            ['nama_skill' => 'CodeIgniter', 'kategori_skill' => 'Pemrograman'],
            ['nama_skill' => 'Node.js', 'kategori_skill' => 'Pemrograman'],
            ['nama_skill' => 'React.js', 'kategori_skill' => 'Pemrograman'],
            ['nama_skill' => 'Vue.js', 'kategori_skill' => 'Pemrograman'],
            ['nama_skill' => 'Flutter', 'kategori_skill' => 'Pemrograman'],
            ['nama_skill' => 'Kotlin', 'kategori_skill' => 'Pemrograman'],
            ['nama_skill' => 'Python', 'kategori_skill' => 'Pemrograman'],
            ['nama_skill' => 'C++', 'kategori_skill' => 'Pemrograman'],
            ['nama_skill' => 'Java', 'kategori_skill' => 'Pemrograman'],
            ['nama_skill' => 'PHP', 'kategori_skill' => 'Pemrograman'],
            ['nama_skill' => 'JavaScript', 'kategori_skill' => 'Pemrograman'],

            // Desain
            ['nama_skill' => 'UI/UX Design', 'kategori_skill' => 'Desain'],
            ['nama_skill' => 'Figma', 'kategori_skill' => 'Desain'],
            ['nama_skill' => 'Adobe XD', 'kategori_skill' => 'Desain'],
            ['nama_skill' => 'Canva', 'kategori_skill' => 'Desain'],
            ['nama_skill' => 'Graphic Design', 'kategori_skill' => 'Desain'],
            ['nama_skill' => 'Adobe Illustrator', 'kategori_skill' => 'Desain'],
            ['nama_skill' => 'Adobe Photoshop', 'kategori_skill' => 'Desain'],

            // Data & AI
            ['nama_skill' => 'Machine Learning', 'kategori_skill' => 'Data & AI'],
            ['nama_skill' => 'Data Science', 'kategori_skill' => 'Data & AI'],
            ['nama_skill' => 'Analisis Data', 'kategori_skill' => 'Data & AI'],
            ['nama_skill' => 'TensorFlow', 'kategori_skill' => 'Data & AI'],
            ['nama_skill' => 'Computer Vision', 'kategori_skill' => 'Data & AI'],
            ['nama_skill' => 'NLP', 'kategori_skill' => 'Data & AI'],

            // Hardware & IoT
            ['nama_skill' => 'IoT', 'kategori_skill' => 'Hardware & IoT'],
            ['nama_skill' => 'Arduino', 'kategori_skill' => 'Hardware & IoT'],
            ['nama_skill' => 'Raspberry Pi', 'kategori_skill' => 'Hardware & IoT'],
            ['nama_skill' => 'Embedded Systems', 'kategori_skill' => 'Hardware & IoT'],
            ['nama_skill' => 'Robotika', 'kategori_skill' => 'Hardware & IoT'],

            // Bisnis & Manajemen
            ['nama_skill' => 'Business Plan', 'kategori_skill' => 'Bisnis & Manajemen'],
            ['nama_skill' => 'Project Management', 'kategori_skill' => 'Bisnis & Manajemen'],
            ['nama_skill' => 'Digital Marketing', 'kategori_skill' => 'Bisnis & Manajemen'],
            ['nama_skill' => 'Presentasi', 'kategori_skill' => 'Bisnis & Manajemen'],

            // Keamanan
            ['nama_skill' => 'Cybersecurity', 'kategori_skill' => 'Keamanan'],
            ['nama_skill' => 'Ethical Hacking', 'kategori_skill' => 'Keamanan'],
            ['nama_skill' => 'Network Security', 'kategori_skill' => 'Keamanan'],

            // Jaringan
            ['nama_skill' => 'Jaringan Komputer', 'kategori_skill' => 'Jaringan'],
            ['nama_skill' => 'Linux Administration', 'kategori_skill' => 'Jaringan'],
            ['nama_skill' => 'Cloud Computing', 'kategori_skill' => 'Jaringan'],
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(
                ['nama_skill' => $skill['nama_skill']],
                ['kategori_skill' => $skill['kategori_skill']]
            );
        }
    }
}
