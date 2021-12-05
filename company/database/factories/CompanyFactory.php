<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail,
            'website' => $this->faker->domainName,
            'logo' => File::inRandomOrder()->first()->id,
        ];
    }
}
