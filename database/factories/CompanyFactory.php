<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $a = ['Wiilards', 'Nestle', 'Netflix', 'GSE', 'Microsoft'];
        return [
            // 'name' => $this->faker->company(),
            'company_name' => $a[array_rand($a)]
        ];
    }        
}