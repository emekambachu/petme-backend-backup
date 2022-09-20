<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\AdminStorePostRequest;
use App\Services\Blog\BlogPostService;
use Illuminate\Http\Request;

class AdminBlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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
                'posts' => $posts,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function publish($id): \Illuminate\Http\JsonResponse
    {
        try {
            $post = $this->post->publishBlogPost($id);
            return response()->json([
                'success' => true,
                'message' => $post['message'],
                'post' => $post['post'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AdminStorePostRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $post = $this->post->storeBlogPost($request);
            return response()->json([
                'success' => true,
                'post' => $post,
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
            $posts = $this->post->searchBlogPost($request);
            return response()->json([
                'success' => true,
                'posts' => $posts['posts'],
                'total' => $posts['total'],
                'search_values' => $posts['search_values'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $post = $this->post->blogPostWithRelations()->findOrFail($id);
            return response()->json([
                'success' => true,
                'post' => $post,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $post = $this->post->updateBlogPost($request, $id);
            return response()->json([
                'success' => true,
                'post' => $post,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->post->deleteBlogPost($id);
            return response()->json([
                'success' => true,
                'message' => 'Deleted',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
