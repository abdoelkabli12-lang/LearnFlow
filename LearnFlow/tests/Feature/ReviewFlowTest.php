<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (env('DB_CONNECTION') === 'sqlite' && ! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('The pdo_sqlite extension is required for the review flow test suite.');
        }

        parent::setUp();
    }

    public function test_enrolled_user_can_create_a_review(): void
    {
        [$student, $course] = $this->createEnrolledStudentAndCourse();

        $response = $this->actingAs($student)->post(route('reviews.store', $course), [
            'rating' => 5,
            'comment' => 'Excellent course with practical lessons.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reviews', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'rating' => 5,
            'comment' => 'Excellent course with practical lessons.',
        ]);
    }

    public function test_user_cannot_review_course_without_accepted_enrollment(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $course = $this->createCourse();

        $response = $this->actingAs($student)->post(route('reviews.store', $course), [
            'rating' => 4,
            'comment' => 'I should not be able to post this.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('review');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_user_can_only_update_their_own_review(): void
    {
        [$student, $course] = $this->createEnrolledStudentAndCourse();
        [$otherStudent] = $this->createEnrolledStudentAndCourse($course);

        $review = Review::create([
            'rating' => 3,
            'comment' => 'Initial review text.',
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($otherStudent)->patch(route('reviews.update', $review), [
            'rating' => 5,
            'comment' => 'Trying to edit another user review.',
        ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 3,
            'comment' => 'Initial review text.',
        ]);
    }

    private function createEnrolledStudentAndCourse(?Course $course = null): array
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $course ??= $this->createCourse();

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => 'accepted',
            'progress' => 0,
        ]);

        return [$student, $course];
    }

    private function createCourse(): Course
    {
        $host = User::factory()->create([
            'role' => 'host',
        ]);

        $category = Category::create([
            'name' => 'Development',
            'slug' => 'development',
        ]);

        return Course::create([
            'title' => 'Laravel Reviews',
            'description' => 'Course used for review flow testing.',
            'price' => 49.99,
            'level' => 'Beginner',
            'is_published' => true,
            'user_id' => $host->id,
            'category_id' => $category->id,
        ]);
    }
}
