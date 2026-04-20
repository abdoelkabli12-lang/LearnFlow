<?php

namespace Tests\Unit;

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
use App\Policies\LessonPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\UserPolicy;
use PHPUnit\Framework\TestCase;

class PolicyAuthorizationTest extends TestCase
{
    public function test_category_policy_is_admin_only(): void
    {
        $policy = new CategoryPolicy();
        $admin = $this->makeUser(1, 'admin');
        $host = $this->makeUser(2, 'host');
        $category = new Category();

        $this->assertTrue($policy->create($admin));
        $this->assertTrue($policy->update($admin, $category));
        $this->assertFalse($policy->create($host));
        $this->assertFalse($policy->delete($host, $category));
    }

    public function test_course_policy_allows_owner_or_admin_to_manage(): void
    {
        $policy = new CoursePolicy();
        $owner = $this->makeUser(10, 'host');
        $otherHost = $this->makeUser(11, 'host');
        $admin = $this->makeUser(1, 'admin');
        $course = new Course(['user_id' => 10, 'is_published' => false]);

        $this->assertTrue($policy->update($owner, $course));
        $this->assertFalse($policy->update($otherHost, $course));
        $this->assertTrue($policy->publish($admin, $course));
        $this->assertFalse($policy->view($otherHost, $course));
    }

    public function test_lesson_policy_allows_free_owner_and_enrolled_access(): void
    {
        $policy = new LessonPolicy();
        $host = $this->makeUser(5, 'host');
        $student = $this->makeUser(6, 'student');
        $otherStudent = $this->makeUser(7, 'student');

        $course = new Course(['user_id' => 5]);
        $course->setRelation('enrollments', collect([
            new Enrollment(['user_id' => 6, 'status' => 'accepted']),
        ]));

        $module = new Module(['course_id' => 1]);
        $module->setRelation('course', $course);

        $freeLesson = new Lesson(['is_free' => true]);
        $freeLesson->setRelation('module', $module);

        $paidLesson = new Lesson(['is_free' => false]);
        $paidLesson->setRelation('module', $module);

        $this->assertTrue($policy->view($host, $paidLesson));
        $this->assertTrue($policy->view($student, $paidLesson));
        $this->assertTrue($policy->view($otherStudent, $freeLesson));
        $this->assertFalse($policy->view($otherStudent, $paidLesson));
    }

    public function test_payment_policy_limits_access_to_owner_or_admin(): void
    {
        $policy = new PaymentPolicy();
        $owner = $this->makeUser(9, 'student');
        $other = $this->makeUser(10, 'student');
        $admin = $this->makeUser(1, 'admin');
        $payment = new Payment(['user_id' => 9]);

        $this->assertTrue($policy->view($owner, $payment));
        $this->assertFalse($policy->view($other, $payment));
        $this->assertTrue($policy->update($admin, $payment));
        $this->assertFalse($policy->delete($owner, $payment));
    }

    public function test_user_policy_blocks_self_delete_and_allows_self_view(): void
    {
        $policy = new UserPolicy();
        $admin = $this->makeUser(1, 'admin');
        $target = $this->makeUser(2, 'student');
        $sameUser = $this->makeUser(3, 'student');

        $this->assertTrue($policy->view($sameUser, $sameUser));
        $this->assertTrue($policy->delete($admin, $target));
        $this->assertFalse($policy->delete($admin, $admin));
    }

    public function test_review_policy_allows_admin_or_owner_to_delete(): void
    {
        $policy = new \App\Policies\ReviewPolicy();
        $author = $this->makeUser(3, 'student');
        $admin = $this->makeUser(1, 'admin');
        $other = $this->makeUser(4, 'student');
        $review = new Review(['user_id' => 3]);

        $this->assertTrue($policy->delete($author, $review));
        $this->assertTrue($policy->delete($admin, $review));
        $this->assertFalse($policy->delete($other, $review));
    }

    private function makeUser(int $id, string $role): User
    {
        $user = new User();
        $user->id = $id;
        $user->role = $role;

        return $user;
    }
}
