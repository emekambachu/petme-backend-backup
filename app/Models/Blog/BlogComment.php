<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;
    protected $fillable = [
      'blog_post_id',
      'ip',
      'name',
      'comment',
      'status',
    ];

    public function blog_post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id', 'id');
    }

}
