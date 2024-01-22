<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    use HasFactory;
  
    public $timestamps = false;

    protected $table = 'article_image';
    protected $fillable = [
        'file_name',
        'article_id',
    ];

    protected $hidden = [
        'file_name',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }
}
