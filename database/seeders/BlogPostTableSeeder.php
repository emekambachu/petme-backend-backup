<?php

namespace Database\Seeders;

use App\Models\Blog\BlogComment;
use App\Models\Blog\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogPost::factory(5)->create()->each(function ($post) {
            $post->blog_comments()->saveMany(BlogComment::factory(2)->make());
        });
    }
}
