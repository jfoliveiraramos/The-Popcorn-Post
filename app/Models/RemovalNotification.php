<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemovalNotification extends Model
{
    use HasFactory;

    protected $table = "removal_notification";
    public $timestamps = false;
    protected $fillable = [
        "id",
    ];

    public function contentNotification() {
        return $this->belongsTo(ContentNotification::class, "id", "id");
    }

    public function content_item() {
        return $this->contentNotification->content_item;
    }

    public function notified_user() {
        return $this->contentNotification->notification->notified_user;
    }

    public function wasRead() {
        return $this->contentNotification->notification->was_read;
    }

    public function date() {
        $dateTime = new DateTime($this->contentNotification->notification->date_time);
        return $dateTime->format('d/m/Y');
    }

    public function time() {
        $dateTime = new DateTime($this->contentNotification->notification->date_time);
        return $dateTime->format('H:i');
    }
}
