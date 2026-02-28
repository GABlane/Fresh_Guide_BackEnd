<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Origin extends Model
{
    protected $fillable = ['name', 'code', 'description'];

    public function routes(): HasMany
    {
        return $this->hasMany(CampusRoute::class);
    }
}
