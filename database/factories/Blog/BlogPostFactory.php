<?php

namespace Database\Factories\Blog;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'author' => $this->faker->name(),
            'description' => $this->faker->paragraph(),
            'blog_category_id' => $this->faker->numberBetween(1, 4),
            'photo' => $this->faker->imageUrl(700, 400),
            'photo_path' => '/photos/blog/posts',
            'status' => 'published',
        ];
    }
}
