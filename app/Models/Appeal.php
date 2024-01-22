<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    use HasFactory;

    public $timestamps  = false;

    protected $table = 'appeal';

    protected $fillable = [
        'body',
        'submitter_id',
    ];

    public function submitter()
    {
        return $this->belongsTo(Member::class, 'submitter_id', 'id');
    }

    public function date() 
    {
        return date('F j, Y', strtotime($this->date_time));
    }

    public function time() 
    {
        return date('g:i A', strtotime($this->date_time));
    }

    static function getAllAppeals()
    {
        return Appeal::orderBy('id')->get();
    }
}
