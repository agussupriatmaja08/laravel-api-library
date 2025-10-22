<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Loan;
use App\Models\User;

class DummySeeder extends Seeder
{
    public function run(): void
    {
        // Seeder untuk data dummy
        User::factory(100)->create();
        Author::factory(2000)->create();
        Category::factory(2000)->create();
        Publisher::factory(300)->create();
        Book::factory(3000)->create();
        Loan::factory(200)->create();
    }
}
