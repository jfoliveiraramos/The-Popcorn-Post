<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    public $timestamps  = false;

    protected $table = 'topic';

    protected $fillable = [
        'name',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class, 'topic_id', 'id');
    }

    public static function getAllTopics()
    {
        return self::all();
    }
}
