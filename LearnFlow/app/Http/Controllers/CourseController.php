<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        $selectedCategories = collect($request->input('category', $request->input('categories', [])))
            ->flatten()
            ->filter()
            ->map(fn ($category) => (int) $category)
            ->unique()
            ->values();

        $selectedLevel = $request->string('level')->toString();
        $selectedSort = $request->string('sort')->toString() ?: 'most_relevant';
        $selectedRating = (int) $request->input('rating', 0);
        $search = trim($request->string('search')->toString());

        $query = Course::published()
            ->with(['category', 'user'])
            ->withCount([
                'reviews',
                'enrollments as accepted_enrollments_count' => fn ($enrollments) => $enrollments->where('status', 'accepted'),
            ])
            ->withAvg('reviews', 'rating');

        $applyRatingOrder = function ($builder) {
            $driver = $builder->getConnection()->getDriverName();

            if ($driver === 'pgsql') {
                $builder->orderByRaw('reviews_avg_rating desc nulls last');

                return;
            }

            $builder->orderByDesc('reviews_avg_rating');
        };

        if ($selectedCategories->isNotEmpty()) {
            $query->whereIn('category_id', $selectedCategories->all());
        }

        if ($selectedLevel !== '') {
            $query->byLevel($selectedLevel);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%'))
                    ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', '%' . $search . '%'));
            });
        }

        if ($selectedRating > 0) {
            $query->whereRaw(
                '(select coalesce(avg(reviews.rating), 0) from reviews where reviews.course_id = courses.id) >= ?',
                [$selectedRating]
            );
        }

        match ($selectedSort) {
            'newest' => $query->latest(),
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'highest_rated' => $query
                ->tap($applyRatingOrder)
                ->orderByDesc('reviews_count')
                ->latest(),
            'popular' => $query
                ->orderByDesc('accepted_enrollments_count')
                ->orderByDesc('reviews_count')
                ->latest(),
            default => $query
                ->tap($applyRatingOrder)
                ->orderByDesc('accepted_enrollments_count')
                ->orderByDesc('reviews_count')
                ->latest(),
        };

        $courses = $query->paginate(12)->withQueryString();
        $categories = Category::withCount([
            'courses as courses_count' => fn ($coursesQuery) => $coursesQuery->published(),
        ])->orderBy('name')->get();

        $selectedCategoryIds = $selectedCategories->all();
        $levels = ['Beginner', 'Intermediate', 'Advanced', 'All'];
        $sortOptions = [
            'most_relevant' => 'Most Relevant',
            'newest' => 'Newest',
            'highest_rated' => 'Highest Rated',
            'popular' => 'Most Popular',
            'price_low' => 'Price: Low to High',
            'price_high' => 'Price: High to Low',
        ];

        return view('courses.index', [
            'courses' => $courses,
            'categories' => $categories,
            'levels' => $levels,
            'sortOptions' => $sortOptions,
            'selectedCategoryIds' => $selectedCategoryIds,
            'selectedLevel' => $selectedLevel,
            'selectedSort' => $selectedSort,
            'selectedRating' => $selectedRating,
            'search' => $search,
            'resultLabel' => Str::of(number_format($courses->total()))->append(' premium courses curated for your career growth.'),
        ]);
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
