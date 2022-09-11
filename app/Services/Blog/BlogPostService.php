<?php

namespace App\Services\Blog;

use App\Models\Blog\BlogPost;
use Illuminate\Support\Facades\File;

/**
 * Class BlogPostService.
 */
class BlogPostService
{
    public function blogPost(): BlogPost
    {
        return new BlogPost();
    }

    public function blogPostWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->blogPost()
            ->with('blog_category', 'blog_comments', 'blog_likes', 'blog_views');
    }

    public function storeBlogPost($request){
        $input = $request->all();
        if($request->has('photo')){
            $file = $request->file('photo');
            $path = '/photos/blog/posts';
            if (!File::exists($path)){
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $name = time() . $file->getClientOriginalName();
            //Move image to photos directory
            $file->move($path, $name);
            $input['photo'] = $name;
        }
        return $this->blogPost()->create($input);
    }

    public function publishBlogPost($id): array
    {
        $post = $this->blogPost()->findOrFail($id);
        $message = '';
        if($post->status === 'published'){
            $post->status = 'pending';
            $message = $post->title.' is now hidden';
        }else{
            $post->status = 'published';
            $message = $post->tile.' is now published';
        }
        $post->save();
        return [
            'post' => $post,
            'message' => $message,
        ];
    }

    public function searchBlogPost($request): array
    {
        $input = $request->all();
        $request->session()->forget(['search_inputs']);

        // Create empty array for search values session
        // Add all input to search inputs session, can be easily passed to export functionality
        $request->session()->put('search_inputs', $input);
        $searchValues = [];

        if(!empty($input['term'])) {
            $searchValues['term'] = $input['term'];
        }

        $posts = $this->blogPostWithRelations()
            ->select(
                'blog_posts.*',
                'blog_posts.id AS blog_post_id',
                'blog_posts.title AS blog_post_title',
                'blog_categories.id',
                'blog_categories.name',
            )->leftjoin('blog_categories',
                'blog_categories.id', '=', 'blog_posts.blog_category_id'
            )->where(function($query) use ($input){
                // The rest of the queries can come here
                $query->when(!empty($input['term']), static function($q) use($input){
                    $q->where('blog_posts.title', 'like' , '%'. $input['term'] .'%')
                        ->orWhere('blog_categories.name', 'like' , '%'. $input['term'] .'%');
                });
            })->paginate(15);

        // if result exists return results, else return empty array
        if($posts->total() > 0){
            return [
                'posts' => $posts,
                'total' => $posts->total(),
                'search_values' => $searchValues
            ];
        }

        return [
            'posts' => [],
            'total' => 0,
            'search_values' => $searchValues
        ];
    }

    public function updateBlogPost($request, $id)
    {
        $input = $request->all();
        $post = $this->blogPost()->findOrFail($id);

        if($request->has('photo')){
            $file = $request->file('photo');
            $path = '/photos/blog/posts';
            if (!File::exists($path)){
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $name = time() . $file->getClientOriginalName();
            //Move image to photos directory
            $file->move($path, $name);
            $input['photo'] = $name;
        }

        $post->update($input);
        return $post;
    }

    public function deleteBlogPost($id): void
    {
        $post = $this->blogPostWithRelations()->findOrFail($id);

        // Delete photo
        if(!empty($post->photo) && File::exists(public_path() .'/photos/blog/posts/'. $post->photo)) {
            FILE::delete(public_path() . '/photos/blog/posts/' . $post->photo);
        }

        // Get all relations
        $comments = $post->blog_comments;
        $likes = $post->blog_likes;
        $views = $post->blog_views;

        // Delete relations
        if($comments->count() > 0){
            foreach($comments as $comment){
                $comment->delete();
            }
        }

        if($likes->count() > 0){
            foreach($likes as $like){
                $like->delete();
            }
        }

        if($views->count() > 0){
            foreach($views as $view){
                $view->delete();
            }
        }

        $post->delete();
    }

}
