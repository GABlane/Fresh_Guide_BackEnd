<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = ['floor_id', 'name', 'code', 'type', 'description'];

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'room_facilities');
    }

    public function routes(): HasMany
    {
        return $this->hasMany(CampusRoute::class, 'destination_room_id');
    }
}
