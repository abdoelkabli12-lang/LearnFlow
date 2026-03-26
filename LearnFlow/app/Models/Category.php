<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    protected static function booted(): void {
        static::creating(function (Category $category){
            $category->slug = str($category->name)->slug();
        });

        static::updating(function (Category $category) {
            if ($category->isDirty('name')) {
                $category->slug = str($category->name)->slug();
            }
        });
    }


    public function courses (): HasMany {
        return $this->hasMany(Course::class);
        }
}
