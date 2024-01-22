<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'profile_image';

    protected $fillable = [
        'file_name',
    ];

    protected $hidden = [
        'file_name',
    ];


    public function member() {
        return $this->belongsTo(Member::class);
    }
}
