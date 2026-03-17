<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleEntry extends Model
{
    protected $table = 'schedule_entries';

    protected $fillable = [
        'user_id',
        'client_uuid',
        'title',
        'course_code',
        'instructor',
        'notes',
        'color_hex',
        'day_of_week',
        'start_minutes',
        'end_minutes',
        'is_online',
        'room_id',
        'online_platform',
        'reminder_minutes',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'start_minutes' => 'integer',
        'end_minutes' => 'integer',
        'is_online' => 'boolean',
        'room_id' => 'integer',
        'reminder_minutes' => 'integer',
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
