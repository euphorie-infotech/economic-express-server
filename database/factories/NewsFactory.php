<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;

class NewsFactory extends Factory
{
    protected $model = Brand::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categories = Category::where('status', 1)->pluck('id')->toArray();
        $tags = Tag::where('status', 1)->pluck('id')->toArray();

        return [
            'unique_id' => $this->faker->randomDigit,
            'title' => $this->faker->sentence,
            'description' => $this->faker->words(100),
            'author' => 'Desk Report',
            'is_published' => $this->faker->numberBetween($min=0, $max=1),
            'is_featured' => $this->faker->numberBetween($min=0, $max=1),
            'category_id' => json_encode(["0" => $this->faker->randomElement($categories)]),
            'tag_id' => json_encode(["0" => $this->faker->randomElement($tags)]),
            'status' => $this->faker->numberBetween($min=0, $max=1),
            'created_by' => '1',
        ];
    }
}
