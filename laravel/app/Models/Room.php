<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Room extends Model
{
    protected $fillable = ['floor_id', 'name', 'code', 'type', 'description', 'image_url', 'location'];

    protected $appends = ['image_full_url'];

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

    public function getImageFullUrlAttribute(): ?string
    {
        if (empty($this->image_url)) {
            return null;
        }

        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://')) {
            return $this->image_url;
        }

        $relativeUrl = Storage::disk('public')->url($this->image_url);
        if (app()->bound('request')) {
            $request = app('request');
            if (method_exists($request, 'getSchemeAndHttpHost')) {
                return rtrim($request->getSchemeAndHttpHost(), '/') . $relativeUrl;
            }
        }

        return $relativeUrl;
    }
}
