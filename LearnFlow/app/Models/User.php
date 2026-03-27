<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;


    protected $fillable = ['name', 'email', 'phone', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function courses(){
        return $this->hasMany(Course::class, 'user_id');
    }

    public function enrollments(){
        return $this->hasMany(Enrollment::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function enrolledCourses() {
        return $this->belongsToMany(Course::class, 'enrollments')->withPivot('progress', 'completed_at')->withTimestamps();
    }
}
