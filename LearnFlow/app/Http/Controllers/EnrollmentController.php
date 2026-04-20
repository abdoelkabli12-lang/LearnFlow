<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Enrollment::class);

        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with(['course.category', 'course.modules.lessons', 'payment'])
            ->latest()
            ->get();

        return view('enrollment.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Enrollment $enrollment)
    {
        $this->authorize('view', $enrollment);

        $enrollment->load(['course.category', 'course.modules.lessons', 'payment']);

        return view('enrollment.show', compact('enrollment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnrollmentRequest $request)
    {
        $validated = $request->validated();

        $enrollment = Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $validated['course_id'],
            'enrolled_at' => $validated['enrolled_at'] ?? now(),
            'status' => $validated['status'] ?? 'pending',
            'progress' => $validated['progress'] ?? 0,
        ]);

        return redirect()
            ->route('enrollments.show', $enrollment)
            ->with('success', 'Enrollment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function cancel(Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);

        $enrollment->update(['status' => 'cancelled']);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment cancelled successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);

        $validated = $request->validated();

        $enrollment->update($validated);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $this->authorize('delete', $enrollment);

        $enrollment->delete();

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment deleted successfully.');
    }
}
