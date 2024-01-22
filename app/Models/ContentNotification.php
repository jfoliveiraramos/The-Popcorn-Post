<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentNotification extends Model
{
    use HasFactory;

    protected $table = "content_notification";
    public $timestamps = false;
    protected $fillable = [
        "id",
        "content_item_id",
    ];

    public function notification() {
        return $this->belongsTo(Notification::class, "id", "id");
    }

    public function content_item() {
        return $this->belongsTo(ContentItem::class, "content_item_id", "id");
    }

    public function upvoteNotification() {
        return $this->hasOne(UpvoteNotification::class, "id", "id");
    }

    public function editNotification() {
        return $this->hasOne(EditNotification::class, "id", "id");
    }

    public function removalNotification() {
        return $this->hasOne(RemovalNotification::class, "id", "id");
    }

    public function undefinedTopicNotification() {
        return $this->hasOne(UndefinedTopicNotification::class, "id", "id");
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
