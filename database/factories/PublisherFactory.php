<?php

namespace Database\Factories;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PublisherFactory extends Factory
{
    protected $model = Publisher::class;

    public function definition(): array
    {
        return [
            'name' => Str::limit($this->faker->company, 50, ''),
            'address' => $this->faker->address,
            'website' => $this->faker->url,

        ];
    }
}
