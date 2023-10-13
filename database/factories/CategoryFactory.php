<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_en' => $this->faker->word(3),
            'name_bn' => $this->faker->word(3),
            'meta_title' => $this->faker->word(3),
            'meta_keyword' => $this->faker->word(3),
            'meta_description' => $this->faker->paragraph(1),
            'status' => $this->faker->numberBetween($min=0, $max=1),
            'created_by' => '1',
        ];
    }
}
