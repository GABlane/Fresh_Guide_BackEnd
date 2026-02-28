<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Named CampusRoute to avoid conflict with Illuminate\Support\Facades\Route
class CampusRoute extends Model
{
    protected $table = 'routes';

    protected $fillable = ['origin_id', 'destination_room_id', 'name', 'description'];

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Origin::class);
    }

    public function destinationRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'destination_room_id');
    }

    public function steps(): HasMany
    {
        return $this->hasMany(RouteStep::class, 'route_id')->orderBy('order');
    }
}
