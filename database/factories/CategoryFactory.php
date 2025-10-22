<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => Str::limit($this->faker->word, 20, ''),
            'description' => Str::limit($this->faker->sentence, 50, ''),
        ];
    }
}
