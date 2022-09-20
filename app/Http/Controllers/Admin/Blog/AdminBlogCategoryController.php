<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\AdminStorePostCategoryRequest;
use App\Services\Blog\BlogCategoryService;
use Illuminate\Http\Request;

class AdminBlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private $category;
    public function __construct(BlogCategoryService $category){
        $this->category = $category;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $categories = $this->category->blogCategory()
                ->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'categories' => $categories,
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
            $category = $this->category->blogCategoryById($id);
            return response()->json([
                'success' => true,
                'category' => $category,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(AdminStorePostCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $category = $this->category->blogCategory()
                ->create($request->all());
            return response()->json([
                'success' => true,
                'category' => $category,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $category = $this->category->blogCategoryById($id);
            $category->update($request->all());
            return response()->json([
                'success' => true,
                'category' => $category,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->category->blogCategoryById($id)->delete();
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
