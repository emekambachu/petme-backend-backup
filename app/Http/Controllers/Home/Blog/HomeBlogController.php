<?php

namespace App\Http\Controllers\Home\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\Blog\HomeBlogSubmitCommentRequest;
use App\Http\Resources\Blog\BlogPostCollection;
use App\Http\Resources\Blog\BlogPostResource;
use App\Services\Blog\BlogCommentService;
use App\Services\Blog\BlogPostService;
use Illuminate\Http\Request;

class HomeBlogController extends Controller
{
    private $post;
    private $comment;
    public function __construct(BlogPostService $post, BlogCommentService $comment){
        $this->post = $post;
        $this->comment = $comment;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $posts = $this->post->blogPostWithRelations()
                ->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'posts' => new BlogPostCollection($posts),
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

    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->post->searchBlogPosts($request, $this->post->blogPostApprovedJoins());
            return response()->json([
                'success' => true,
                'posts' => $data['posts'],
                'total' => $data['total'],
                'search_values' => $data['search_values'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function addComment(HomeBlogSubmitCommentRequest $request, $postId){
        try {
            $comment = $this->comment->addCommentToPost($request, $postId);
            return response()->json([
                'success' => true,
                'comment' => $comment,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getPostComments($postId): \Illuminate\Http\JsonResponse
    {
        try {
            $comments = $this->comment->commentsByPostId($postId)->latest()->paginate(10);
            return response()->json([
                'success' => true,
                'comments' => $comments,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }



}
