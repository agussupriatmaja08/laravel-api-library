<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::inRandomOrder()->first()->id ?? Book::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'loaned_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'returned_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement(['borrowed', 'returned']),
        ];
    }
}
