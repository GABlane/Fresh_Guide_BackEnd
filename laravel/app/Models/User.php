<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER  = 'user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
        'campus_code',
        'course_section',
        'profile_photo_path',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'last_login_at' => 'datetime',
        'password'      => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER || $this->role === 'viewer';
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (empty($this->profile_photo_path)) {
            return null;
        }

        if (str_starts_with($this->profile_photo_path, 'http://') || str_starts_with($this->profile_photo_path, 'https://')) {
            return $this->profile_photo_path;
        }

        $publicUrl = Storage::disk('public')->url($this->profile_photo_path);

        if (! app()->bound('request')) {
            return $publicUrl;
        }

        $request = app('request');
        if (! method_exists($request, 'getSchemeAndHttpHost')) {
            return $publicUrl;
        }

        $host = rtrim($request->getSchemeAndHttpHost(), '/');

        if (str_starts_with($publicUrl, 'http://') || str_starts_with($publicUrl, 'https://')) {
            $parts = parse_url($publicUrl);
            $urlHost = $parts['host'] ?? null;

            if ($urlHost && in_array($urlHost, ['localhost', '127.0.0.1', '::1'], true)) {
                $path = $parts['path'] ?? '';
                $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
                return $host . $path . $query;
            }

            return $publicUrl;
        }

        if (! str_starts_with($publicUrl, '/')) {
            $publicUrl = '/' . $publicUrl;
        }

        return $host . $publicUrl;
    }
}
