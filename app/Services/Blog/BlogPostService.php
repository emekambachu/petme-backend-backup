<?php

namespace App\Services\Blog;

use App\Models\Blog\BlogCategory;
use App\Models\Blog\BlogPost;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

/**
 * Class BlogPostService.
 */
class BlogPostService
{
    protected $imagePath = 'photos/blog/posts';

    public function blogPost(): BlogPost
    {
        return new BlogPost();
    }

    public function blogPostWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->blogPost()
            ->with('blog_category', 'blog_comments', 'blog_likes', 'blog_views');
    }

    public function blogPostById($id){
        return $this->blogPostWithRelations()->findOrFail($id);
    }

    public function blogCategory()
    {
        return new BlogCategory();
    }

    public function blogCategoryById($id)
    {
        return $this->blogCategory()->findOrFail($id);
    }

    public function storeBlogPost($request){

        $input = $request->all();
        $input['photo'] = $this->compressAndUploadImage($request, $this->imagePath, 700, 400);
        $input['photo_path'] = '/'.$this->imagePath.'/';
        return $this->blogPost()->create($input);
    }

    public function publishBlogPost($id): array
    {
        $post = $this->blogPostById($id);
        $message = '';
        if($post->status === 'published'){
            $post->status = 'pending';
            $message = $post->title.' is now hidden';
        }else{
            $post->status = 'published';
            $message = $post->title.' is now published';
        }
        $post->save();
        return [
            'post' => $post,
            'message' => $message,
        ];
    }

    public function searchBlogPosts($request): array
    {
        $input = $request->all();
        // Array for storing search results
        $searchValues = [];

        if(!empty($input['term'])) {
            $searchValues['term'] = $input['term'];
        }

        if(!empty($input['category_id'])) {
            $searchValues['category'] = $this->blogCategoryById($input['category_id'])->first()->name;
        }

        $posts = $this->blogPostWithRelations()->select(
                'blog_posts.*',
                'blog_categories.id AS blog_categories_id',
            )->leftjoin('blog_categories',
                'blog_categories.id', '=', 'blog_posts.blog_category_id'
            )->where(function($query) use ($input){
                // The rest of the queries can come here
                $query->when(!empty($input['term']), static function($q) use($input){
                    $q->where('blog_categories.title', 'like' , '%'. $input['term'] .'%')
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
        $post = $this->blogPostById($id);
        // store previous image in session
        Session::put('previous_image', $post->photo);
        // Compress and upload image
        $photo = $this->compressAndUploadImage($request, $this->imagePath, 700, 400);
        if($photo){
            $input['photo'] = $photo;
        }else{
            $input['photo'] = $post->photo;
        }
        $post->update($input);
        // Delete previous image if it was updated
        if(Session::get('previous_image') !== $post->photo){
            $this->deleteImage(Session::get('previous_image'));
        }
        return $post;
    }

    public function deleteBlogPost($id): void
    {
        $post = $this->blogPostById($id);
        $this->deleteImage($post->photo);
        $post->delete();
    }

    protected function compressAndUploadImage($request, $path, $width, $height)
    {
        if($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            // create path to directory
            if (!File::exists($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            $background = Image::canvas($width, $height);
            // start image conversion (Must install Image Intervention Package first)
            $convert_image = Image::make($file->path());
            // resize image and save to converted path
            // resize and fit width
            $convert_image->resize($width, $height, static function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            // insert image to canvas
            $background->insert($convert_image, 'center');
            $background->save($path.'/'.$name);
            // Return full image upload path
            return $name;
        }
        return false;
    }

    protected function uploadImageOnly($file, $path): string
    {
        $name = time() . $file->getClientOriginalName();
        $file->move(public_path($path), $name);
        return $name;
    }

    protected function deleteImage($image): void
    {
        if(File::exists(public_path() . '/'.$this->imagePath.'/' . $image)){
            FILE::delete(public_path() . '/'.$this->imagePath.'/' . $image);
        }
    }

}
