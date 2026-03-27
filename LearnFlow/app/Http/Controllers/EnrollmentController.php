<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Enrollement;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enrollments = Enrollment::where('user_id', Auth::id())->with('course.category', 'course.modules')->latest()->get();

        return view('enrollment.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Enrollment $enrollment)
    {
        if ($enrollment->user_id !== Auth::id()){
            abort(403);
        }

        $enrollment->load('course.moduls.lessons');

        return view('enrollment.show', compact('enrollment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnrollmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function cancel(Enrollment $enrollment)
    {
    if ($enrollment->user_id !== Auth::id()) {
        abort(403);
    }
    
        $enrollment->update(['status' => 'cancelled']);

    return redirect()->route('enrollments.index')
                     ->with('success', 'Enrollment cancelled successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnrollementRequest $request, Enrollment $enrollement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollement)
    {
        //
    }
}
