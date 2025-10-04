<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('12345678'),

        ]);

        DB::table('categories')->insert([
            ['name' => 'Fiksi', 'description' => 'Karya sastra dan cerita imajinatif'],
            ['name' => 'Non-Fiksi', 'description' => 'Buku berbasis fakta dan informasi nyata'],
            ['name' => 'Biografi', 'description' => 'Kisah hidup seseorang'],
            ['name' => 'Sains & Teknologi', 'description' => 'Ilmu pengetahuan dan teknologi'],
            ['name' => 'Sejarah', 'description' => 'Buku tentang peristiwa sejarah'],
            ['name' => 'Self-Improvement', 'description' => 'Pengembangan diri dan motivasi'],
            ['name' => 'Anak-anak', 'description' => 'Buku bacaan untuk anak-anak'],
            ['name' => 'Agama', 'description' => 'Buku keagamaan dan spiritual'],
            ['name' => 'Pendidikan', 'description' => 'Materi ajar dan referensi akademik'],
        ]);
    }
}
