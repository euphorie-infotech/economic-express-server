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
        $date = '2023-10-29';
        $folderPath = '/uploads/epapers/' . $date;
        
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        return [
            'image' => '/uploads/epapers/' . $this->faker->image($path = public_path($folderPath), $width = 900, $height = 2280, 'newspaper', false),
            'page_no' => $this->faker->numberBetween($min=1, $max=8),
            'publish_date' => $date,
            'created_by' => '1'
        ];
    }
}
