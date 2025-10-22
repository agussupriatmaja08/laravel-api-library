<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'author_id' => Author::inRandomOrder()->first()->id ?? Author::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'publisher_id' => Publisher::inRandomOrder()->first()->id ?? Publisher::factory(),
            'published_at' => $this->faker->date(),
            'stock' => $this->faker->numberBetween(0, 20),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}
