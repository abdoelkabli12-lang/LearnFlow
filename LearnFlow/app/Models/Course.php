<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{
    protected $fillable = ['title','slug','description','thumbnail', 'price','level','is_published','category_id','user_id',];

    protected $casts = [
        'is_published' => 'boolean',
        'price'        => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            $course->slug = str($course->title)->slug();
        });

        static::updating(function (Course $course) {
            if ($course->isDirty('title')) {
                $course->slug = str($course->title)->slug();
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeByLevel(Builder $query, string $level): Builder
    {
        return $query->where('level', $level);
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('order_number');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, Module::class);
    }
}
