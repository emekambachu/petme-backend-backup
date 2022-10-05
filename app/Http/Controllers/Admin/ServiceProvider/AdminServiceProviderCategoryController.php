<?php

namespace App\Http\Controllers\Admin\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\ServiceProvider\ServiceProviderCategory;
use App\Services\ServiceProvider\ServiceProviderCategoryService;
use Illuminate\Http\Request;

class AdminServiceProviderCategoryController extends Controller
{
    protected $category;
    public function __construct(ServiceProviderCategoryService $category){
        $this->category = $category;
    }

    public function index()
    {
        try {
            $categories = $this->category->serviceProviderCategory()
                ->latest()->paginate(12);
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

    public function store(Request $request)
    {
        try {
            $category = $this->category->createCategory($request);
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

    public function update(Request $request, $id)
    {
        try {
            $category = $this->category->updateCategory($request, $id);
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

    public function delete($id)
    {
        try {
            $this->category->serviceProviderCategoryById($id)->delete();
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
