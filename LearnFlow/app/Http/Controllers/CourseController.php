<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function hostIndex()
    {
        $query = Course::with(['category', 'user'])->latest();

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
        $totalStudents = $course->enrollments()->count();

        return view('courses.show', compact('course', 'averageRating', 'totalStudents'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('host.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'level'       => 'required|in:Beginner,Intermediate,Advanced,All',
            'category_id' => 'required|exists:categories,id',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only('title', 'description', 'price', 'level', 'category_id');
        $data['user_id'] = Auth::id();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course = Course::create($data);

        return redirect()->route('host.courses.show', $course)->with('success', 'Course created successfully.');
    }

    public function update(Request $request, Course $course)
    {
        if (! $this->canManage($course)) {
            abort(403);
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'level'       => 'required|in:Beginner,Intermediate,Advanced,All',
            'category_id' => 'required|exists:categories,id',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only('title', 'description', 'price', 'level', 'category_id');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->update($data);

        return redirect()->route('host.courses.show', $course)->with('success', 'Course updated successfully.');
    }

    public function edit(Course $course)
    {
        if (! $this->canManage($course)) {
            abort(403);
        }

        $categories = Category::orderBy('name')->get();

        return view('host.courses.edit', compact('course', 'categories'));
    }

    public function destroy(Course $course)
    {
        if (! $this->canManage($course)) {
            abort(403);
        }

        $course->delete();

        return redirect()->route('host.courses.index')->with('success', 'Course deleted successfully.');
    }

    public function togglePublish(Course $course)
    {
        if (! $this->canManage($course)) {
            abort(403);
        }

        $course->update(['is_published' => ! $course->is_published]);

        $status = $course->is_published ? 'published' : 'unpublished';

        return back()->with('success', "Course {$status} successfully.");
    }

    private function canManage(Course $course): bool
    {
        $user = Auth::user();

        return (bool) $user && ($user->role === 'admin' || $course->isOwnedBy($user));
    }

    private function canPreview(Course $course): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $this->canManage($course);
    }
}
