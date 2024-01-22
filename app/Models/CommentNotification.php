<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentNotification extends Model
{
    use HasFactory;
    protected $table = "comment_notification";
    public $timestamps = false;
    protected $fillable = [
        "id",
        "comment_id",
    ];

    public function notification() {
        return $this->belongsTo(Notification::class, "id", "id");
    }
    public function comment() {
        return $this->belongsTo(Comment::class, "comment_id", "id");
    }

    public function notified_user() {
        return $this->notification->notified_user;
    }

    public function wasRead() {
        return $this->notification->was_read;
    }

    public function date() {
        $dateTime = new DateTime($this->notification->date_time);
        return $dateTime->format('d/m/Y');
    }

    public function time() {
        $dateTime = new DateTime($this->notification->date_time);
        return $dateTime->format('H:i');
    }
}
