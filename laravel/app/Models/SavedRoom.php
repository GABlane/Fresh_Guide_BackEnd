<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedRoom extends Model
{
    protected $table = 'saved_rooms';

    protected $fillable = [
        'user_id',
        'room_id',
        'saved_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'room_id' => 'integer',
        'saved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
