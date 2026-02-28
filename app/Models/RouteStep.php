<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteStep extends Model
{
    protected $fillable = ['route_id', 'order', 'instruction', 'direction', 'landmark'];

    public function route(): BelongsTo
    {
        return $this->belongsTo(CampusRoute::class, 'route_id');
    }
}
