<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Notification extends Model
{
    use HasFactory;
    protected $table = "notification";
    public $timestamps = false;
    protected $fillable = [
        "notified_id",
    ];

    protected $hidden = [
        "was_read",
    ];

    public function notified_user() {
        return $this->belongsTo(Member::class, "notified_id", "id");
    }

    public function blockNotification() {
        return $this->hasOne(BlockNotification::class, "id", "id");
    }

    public function commentNotification() {
        return $this->hasOne(CommentNotification::class, "id", "id");
    }

    public function contentNotification() {
        return $this->hasOne(ContentNotification::class, "id", "id");
    }

    public function followNotification() {
        return $this->hasOne(FollowNotification::class, "id", "id");
    }

    public function date() {
        $dateTime = new DateTime($this->date_time);
        return $dateTime->format('d/m/Y');
    }

    public function time() {
        $dateTime = new DateTime($this->date_time);
        return $dateTime->format('H:i');
    }

}