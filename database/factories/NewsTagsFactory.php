<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\News;
use App\Models\Tag;
use App\Models\NewsTags;

class NewsTagsFactory extends Factory
{
    protected $model = NewsTags::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tags = Tag::where('status', 1)->pluck('id')->toArray();
        $news = News::where('status', 1)->pluck('id')->toArray();

        return [
            'news_id' => $this->faker->randomElement($news),
            'tag_id' => $this->faker->randomElement($tags),
        ];
    }
}
