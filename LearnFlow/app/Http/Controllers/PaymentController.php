<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Enrollement;

use function Symfony\Component\Clock\now;

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
                if ($course->enrollments()->where('user_id', Auth::id())->exists()){
            return redirect()->route('enrollments.show', $course)->withErrors('error', "You're already enrolled in this course!");
        }

        if ($course->isOwnedBy(Auth::user())){
            return back()->withErrors('error', 'you can not enroll into your own course');
        }

        DB::transaction(function () use ($course){

        $payment = Payment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'enrollment_id' => Enrollment::id(),
            'amount' => $course->price,
            'status' => 'accepted',
            'payment_date' => now(),
        ]);

        $enrollment = Enrollment::create([
            'payment_id' => $payment->id,
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => 'accepted',
            'progress' => 0
        ]);
    });
    return redirect()->route('enrollments.index')->with('success', 'You have successfuly enrolled in teh course');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment, Course $course)
    {

        if ($course->enrollment()->where('user_id', Auth::id())->exists()){
            return redirect()->route('enrollment.show', $course)->withErrors('error', "You're already enrolled in this course!");
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
