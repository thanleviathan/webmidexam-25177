<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'location',
        'starts_at',
        'capacity',
        'poster',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Number of free seats. Uses the withCount value when it was loaded.
     */
    public function seatsLeft(): int
    {
        $taken = $this->getAttribute('registrations_count');

        if ($taken === null) {
            $taken = $this->registrations()->count();
        }

        return max(0, $this->capacity - (int) $taken);
    }
}
