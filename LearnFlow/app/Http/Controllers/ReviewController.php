<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Course;
use App\Models\Review;

class ReviewController extends Controller
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
    public function store(StoreReviewRequest $request, Course $course)
    {
        $this->authorize('create', Review::class);

        $user = $request->user();
        $validated = $request->validated();

        if ($user->id === $course->user_id) {
            return back()
                ->withInput()
                ->withErrors([
                    'review' => "You cannot review your own course.",
                ]);
        }

        $enrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->where('status', 'accepted')
            ->first();

        if (! $enrollment) {
            return back()
                ->withInput()
                ->withErrors([
                    'review' => "You can only review courses you are enrolled in.",
                ]);
        }

        if ($course->reviews()->where('user_id', $user->id)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'review' => "You already reviewed this course.",
                ]);
        }

        Review::create([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        return back()->with('success', 'Review posted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);

        $review->update($request->validated());

        return back()->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
