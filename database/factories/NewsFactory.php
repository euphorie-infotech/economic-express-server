<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\News;
use App\Models\Category;

class NewsFactory extends Factory
{
    protected $model = News::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categories = Category::where('status', 1)->pluck('id')->toArray();

        return [
            'unique_id' => $this->faker->randomDigit,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph(5),
            'author' => 'Desk Report',
            'is_published' => $this->faker->numberBetween($min=0, $max=1),
            'is_featured' => $this->faker->numberBetween($min=0, $max=1),
            'category_id' => $this->faker->randomElement($categories),
            'status' => $this->faker->numberBetween($min=0, $max=1),
            'created_by' => '1',
        ];
    }
}
