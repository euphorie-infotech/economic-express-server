<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;

class CategoryFactory extends Factory
{
    protected $model = Brand::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_en' => $this->faker->unique()->word,
            'name_bn' => $this->faker->unique()->word,
            'meta_title' => $this->faker->word,
            'meta_keyword' => $this->faker->unique()->word,
            'meta_description' => $this->faker->words(100),
            'status' => $this->faker->numberBetween($min=0, $max=1),
            'created_by' => '1',
        ];
    }
}
