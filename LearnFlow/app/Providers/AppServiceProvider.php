<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\CoursePolicy;
use App\Policies\EnrollmentPolicy;
use App\Policies\LessonPolicy;
use App\Policies\ModulePolicy;
use App\Policies\PaymentPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Enrollment::class, EnrollmentPolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);
        Gate::policy(Module::class, ModulePolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
