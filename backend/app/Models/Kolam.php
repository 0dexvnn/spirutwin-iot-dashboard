<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kolam extends Model
{
    protected $fillable = ['code', 'name'];

    public function petani(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'petani_kolam', 'kolam_id', 'petani_id')->withTimestamps();
    }
}
