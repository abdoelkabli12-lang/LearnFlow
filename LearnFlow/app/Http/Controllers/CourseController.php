<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function hostIndex()
    {
        $this->authorize('viewAny', Course::class);

        $query = Course::with(['category', 'user'])
            ->withCount(['modules', 'lessons'])
            ->latest();

        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $courses = $query->paginate(12);

        return view('host.courses.index', compact('courses'));
    }

    public function index(Request $request)
    {
        $query = Course::published()->with(['category', 'user', 'reviews']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('level')) {
            $query->byLevel($request->level);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses    = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::withCount('courses')->get();

        return view('courses.index', compact('courses', 'categories'));
    }

    public function show(Course $course)
    {
        if (! $course->is_published && ! $this->canPreview($course)) {
            abort(404);
        }

        $course->load(['category', 'user', 'modules.lessons', 'reviews.user']);

        $averageRating = $course->reviews->avg('rating');
        $totalStudents = $course->enrollments()->where('status', 'accepted')->count();

        if (request()->routeIs('host.courses.show')) {
            return view('host.courses.show', compact('course', 'averageRating', 'totalStudents'));
        }

        return view('courses.show', compact('course', 'averageRating', 'totalStudents'));
    }

    public function create()
    {
        $this->authorize('create', Course::class);

        $categories = Category::orderBy('name')->get();

        return view('host.courses.create', compact('categories'));
    }

    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course = Course::create($data);

        return redirect()->route('host.courses.show', $course)->with('success', 'Course created successfully.');
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $this->authorize('update', $course);

        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->update($data);

        return redirect()->route('host.courses.show', $course)->with('success', 'Course updated successfully.');
    }

    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        $categories = Category::orderBy('name')->get();

        return view('host.courses.edit', compact('course', 'categories'));
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        $course->delete();

        return redirect()->route('host.courses.index')->with('success', 'Course deleted successfully.');
    }

    public function togglePublish(Course $course)
    {
        $this->authorize('publish', $course);

        $course->update(['is_published' => ! $course->is_published]);

        $status = $course->is_published ? 'published' : 'unpublished';

        return back()->with('success', "Course {$status} successfully.");
    }

    private function canManage(Course $course): bool
    {
        return (bool) auth()->user()?->can('update', $course);
    }

    private function canPreview(Course $course): bool
    {
        return (bool) auth()->user()?->can('view', $course);
    }
}
