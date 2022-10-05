<?php

namespace App\Services\ServiceProvider;

use App\Models\ServiceProvider\ServiceProviderCategory;

/**
 * Class ServiceProviderCategory.
 */
class ServiceProviderCategoryService
{
    public function serviceProviderCategory(): ServiceProviderCategory
    {
        return new ServiceProviderCategory();
    }

    public function serviceProviderCategoryById($id){
        return $this->serviceProviderCategory()->findOrFail($id);
    }

    public function createCategory($request){
        $input = $request->all();
        return $this->serviceProviderCategory()->create($input);
    }

    public function updateCategory($request, $id){
        $category = $this->serviceProviderCategoryById($id);
        $input = $request->all();
        $category->update($input);
        return $category;
    }


}
