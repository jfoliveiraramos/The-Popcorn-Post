<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicSuggestion extends Model
{
    public $timestamps  = false;

    protected $table = 'topic_suggestion';

    protected $fillable = [
        'name',
        'status',
        'suggester_id',
    ];

    public function suggester()
    {
        return $this->belongsTo(Member::class, 'suggester_id', 'id');
    }

    public static function getPendingSuggestions()
    {
        return self::where('status', 'Pending');
    }

    public static function getAcceptedSuggestions()
    {
        return self::where('status', 'Accepted');
    }

    public static function getRejectedSuggestions()
    {
        return self::where('status', 'Rejected');
    }
}
