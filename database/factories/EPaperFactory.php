<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EPaperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = date('Y-m-d', now());
        $folderPath = 'uploads/epapers/' . $date;

        return [
            'image' => $this->faker->image($path = $folderPath, $width = 900, $height = 2280, 'newspaper', false),
            'page_no' => $this->faker->numberBetween($min=1, $max=8),
            'publish_date' => $date,
            'created_by' => '1'
        ];
    }
}
