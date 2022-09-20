<?php

namespace App\Services\Blog;

use App\Models\Blog\BlogCategory;

/**
 * Class BlogCategoryService.
 */
class BlogCategoryService
{
    public function blogCategory()
    {
        return new BlogCategory();
    }

    public function blogCategoryById($id)
    {
        return $this->blogCategory()->findOrFail($id);
    }
}
