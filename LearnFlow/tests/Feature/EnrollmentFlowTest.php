<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (env('DB_CONNECTION') === 'sqlite' && ! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('The pdo_sqlite extension is required for the enrollment flow test suite.');
        }

        parent::setUp();
    }

    public function test_authenticated_user_can_checkout_and_create_enrollment_with_payment(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $host = User::factory()->create([
            'role' => 'host',
        ]);

        $category = Category::create([
            'name' => 'Development',
            'slug' => 'development',
        ]);

        $course = Course::create([
            'title' => 'Laravel Basics',
            'description' => 'Intro course',
            'price' => 49.99,
            'level' => 'Beginner',
            'is_published' => true,
            'user_id' => $host->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($student)->post(route('payment.store', $course));

        $enrollment = Enrollment::first();

        $response->assertRedirect(route('enrollments.show', $enrollment));
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'accepted',
        ]);
        $this->assertDatabaseHas('payments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_id' => $enrollment->id,
            'status' => 'accepted',
        ]);
    }

    public function test_user_with_existing_enrollment_is_redirected_to_existing_record(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $host = User::factory()->create([
            'role' => 'host',
        ]);

        $category = Category::create([
            'name' => 'Design',
            'slug' => 'design',
        ]);

        $course = Course::create([
            'title' => 'UI Foundations',
            'description' => 'Design course',
            'price' => 29.99,
            'level' => 'Beginner',
            'is_published' => true,
            'user_id' => $host->id,
            'category_id' => $category->id,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => 'accepted',
            'progress' => 0,
        ]);

        $response = $this->actingAs($student)->post(route('payment.store', $course));

        $response->assertRedirect(route('enrollments.show', $enrollment));
        $this->assertDatabaseCount('enrollments', 1);
        $this->assertDatabaseCount('payments', 0);
    }
}
