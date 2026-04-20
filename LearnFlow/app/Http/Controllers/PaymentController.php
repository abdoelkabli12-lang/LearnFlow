<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request, Course $course)
    {
        $this->authorize('create', Payment::class);

        if (! $course->is_published) {
            abort(404);
        }

        $existingEnrollment = $course->enrollments()
            ->where('user_id', Auth::id())
            ->where('status', 'accepted')
            ->latest()
            ->first();

        if ($existingEnrollment) {
            return redirect()
                ->route('enrollments.show', $existingEnrollment)
                ->withErrors(['error' => "You're already enrolled in this course."]);
        }

        if ($course->isOwnedBy(Auth::user())) {
            return back()->withErrors(['error' => 'You cannot enroll in your own course.']);
        }

        $enrollment = DB::transaction(function () use ($course) {
            $enrollment = Enrollment::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'enrolled_at' => now(),
                'status' => 'accepted',
                'progress' => 0,
            ]);

            Payment::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'enrollment_id' => $enrollment->id,
                'amount' => $course->price,
                'status' => 'accepted',
                'payment_date' => now(),
            ]);

            return $enrollment;
        });

        return redirect()
            ->route('enrollments.show', $enrollment)
            ->with('success', 'You have successfully enrolled in the course.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $this->authorize('create', Payment::class);

        if (! $course->is_published) {
            abort(404);
        }

        $existingEnrollment = $course->enrollments()
            ->where('user_id', Auth::id())
            ->where('status', 'accepted')
            ->latest()
            ->first();

        if ($existingEnrollment) {
            return redirect()
                ->route('enrollments.show', $existingEnrollment)
                ->withErrors(['error' => "You're already enrolled in this course."]);
        }

        return view('payments.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
