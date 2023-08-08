<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $a = ['Red', 'Blue', 'Green', 'Orange', 'Green'];
        return [
            // 'name' => $this->faker->name(),            
            'campaign_name' => $a[array_rand($a, 1)], 
            'campaign_start' => Carbon::create('2023', '08', '01'),
            'campaign_end' => Carbon::create('2023', '10', '30'),
        ];
    }
}
