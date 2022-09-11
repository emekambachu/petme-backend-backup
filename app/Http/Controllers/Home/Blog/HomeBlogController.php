<?php

namespace App\Http\Controllers\Home\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\BlogPostResource;
use App\Services\Blog\BlogPostService;

class HomeBlogController extends Controller
{
    private $post;
    public function __construct(BlogPostService $post){
        $this->post = $post;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $posts = $this->post->blogPostWithRelations()
                ->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'posts' => BlogPostResource::collection($posts),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $post = $this->post->blogPostWithRelations()->findorFail($id);
            return response()->json([
                'success' => true,
                'post' => new BlogPostResource($post),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
