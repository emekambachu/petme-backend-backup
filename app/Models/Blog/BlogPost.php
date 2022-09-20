<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'author',
        'description',
        'blog_category_id',
        'photo',
        'photo_path',
        'status',
    ];

    public function blog_category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id', 'id');
    }

    public function blog_comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id', 'id');
    }

    public function blog_likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlogLike::class, 'blog_post_id', 'id');
    }
    public function blog_views(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlogView::class, 'blog_post_id', 'id');
    }
}
