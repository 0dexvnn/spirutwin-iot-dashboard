<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'phone',
        'address',
        'nik',
        'no_kk',
        'petani_code',
        'role',
        'email',
        'password',
        'telegram_user_id',
        'telegram_chat_id',
        'telegram_username',
        'telegram_name',
        'telegram_first_name',
        'telegram_last_name',
        'telegram_connected_at',
        'telegram_last_seen_at',
        'telegram_connection_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'telegram_connected_at' => 'datetime',
        'telegram_last_seen_at' => 'datetime',
    ];

    public function kolams(): BelongsToMany
    {
        return $this->belongsToMany(Kolam::class, 'petani_kolam', 'petani_id', 'kolam_id')->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
