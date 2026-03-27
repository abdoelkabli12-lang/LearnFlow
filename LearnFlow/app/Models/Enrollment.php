<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    /** @use HasFactory<\Database\Factories\EnrollementFactory> */
    use HasFactory;

    protected $table = 'enrollments';

    protected $fillable = ['enrolled_at', 'status', 'progress', 'user_id', 'course_id'];

    public function user() {
    return $this->belongsTo(User::class);
}

public function course() {
    return $this->belongsTo(Course::class);
}
}
