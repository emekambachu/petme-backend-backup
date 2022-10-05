<?php

namespace App\Services\Blog;

use App\Models\Blog\BlogComment;
use App\Services\Base\BaseService;

/**
 * Class BlogCommentService.
 */
class BlogCommentService extends BlogPostService
{
    private $base;
    public function __construct(BaseService $base){
        $this->base = $base;
    }

    public function blogComment(): BlogComment
    {
        return new BlogComment();
    }

    public function commentsByPostId($postId){
        return $this->blogComment()->where('blog_post_id', $postId);
    }

    public function addCommentToPost($request, $postId){
        $input = $request->all();
        $input['ip'] = $this->base->getIp();
        $input['blog_post_id'] = $postId;
        return $this->blogComment()->create($input);
    }
}
