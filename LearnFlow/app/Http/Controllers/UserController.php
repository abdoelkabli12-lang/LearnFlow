<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
 

class UserController extends Controller
{

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request) 
    {
        $validated = $request->validate([
            "email" => 'required|string|email|max:50',
            "password" => 'required|string'
        ]);

        if (Auth::attempt($validated, $request->boolean('remember'))){
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user());
        }

        return back()->withInput($request->only('email'))->withErrors(['email' => 'Invalid email or password']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }


    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'visitor',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return $this->redirectByRole($user)->with('success', 'welcome to our website!');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'you have logged out of your account');
    }

    public function dashboard()
    {
        $user = Auth::user();
 
        return match ($user->role) {
            'admin'      => view('admin.dashboard', compact('user')),
            'host'       => view('host.dashboard', compact('user')),
            'student'    => $this->showStudentDashboard($user),
            'visitor'    => view('visitor.dashboard', compact('user')),
            default      => abort(403, 'Rôle non reconnu.'),
        };
    }
 

public function showProfile()
    {
        $user = Auth::user();
        $this->authorize('view', $user);
 
        return view('auth.profile', compact('user'));
    }
 
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $this->authorize('update', $user);
 
        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'bio'    => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);
 
        $data = $request->only('name', 'email', 'bio');
 
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }
 
        $user->update($data);
 
        return back()->with('success', 'Profile updated successfully.');
    }
 
    public function showChangePassword()
    {
        return view('auth.change-password');
    }
 
    public function changePassword(Request $request)
    {
        $user = Auth::user();
 
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);
 
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
 
        $user->update([
            'password' => Hash::make($request->password),
        ]);
 
        Auth::logoutOtherDevices($request->password);
 
        return back()->with('success', 'Password changed successfully.');
    }
 
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();
 
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }
 
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }
 
        $users = $query->latest()->paginate(20)->withQueryString();
 
        return view('admin.users.index', compact('users'));
    }
 
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return view('admin.users.show', compact('user'));
    }
 
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = ['visitor', 'student', 'host', 'admin'];
 
        return view('admin.users.edit', compact('user', 'roles'));
    }
 
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update($request->validated());
 
        return redirect()->route('admin.users.index')
                         ->with('success', "User {$user->name} has been updated.");
    }
 
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
 
        $user->delete();
 
        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }

    private function showStudentDashboard(User $user)
    {
        $enrollments = $user->enrollments()
            ->where('status', 'accepted')
            ->with([
                'course.category',
                'course.user',
                'course.reviews',
                'course.modules.lessons',
            ])
            ->latest('updated_at')
            ->get()
            ->filter(fn (Enrollment $enrollment) => $enrollment->course !== null)
            ->values();

        $learningLibrary = $enrollments
            ->map(fn (Enrollment $enrollment) => $this->makeStudentCourseSnapshot($enrollment))
            ->values();

        $activeCourses = $learningLibrary
            ->where('isCompleted', false)
            ->sortByDesc(fn (array $course) => $course['enrollment']->updated_at?->timestamp ?? 0)
            ->values();

        $completedCourses = $learningLibrary
            ->where('isCompleted', true)
            ->sortByDesc(fn (array $course) => $course['enrollment']->updated_at?->timestamp ?? 0)
            ->values();

        $upcomingLessons = $activeCourses
            ->filter(fn (array $course) => $course['nextLesson'] !== null)
            ->take(4)
            ->values();

        $averageProgress = (int) round($learningLibrary->avg('progress') ?? 0);
        $totalMinutes = (int) $learningLibrary->sum('totalMinutes');

        return view('student.dashboard', [
            'user' => $user,
            'stats' => [
                'activeCourses' => $activeCourses->count(),
                'completedCourses' => $completedCourses->count(),
                'averageProgress' => $averageProgress,
                'hoursOfContent' => round($totalMinutes / 60, 1),
            ],
            'continueLearning' => $activeCourses->take(2)->values(),
            'upcomingLessons' => $upcomingLessons,
            'recommendations' => $this->buildStudentRecommendations($user, $enrollments),
            'learningLibrary' => $learningLibrary->take(5)->values(),
        ]);
    }

    private function buildStudentRecommendations(User $user, Collection $enrollments): Collection
    {
        $enrolledCourseIds = $enrollments->pluck('course_id')->filter()->unique()->values();
        $preferredCategoryIds = $enrollments->pluck('course.category_id')->filter()->unique()->values();

        $baseQuery = fn () => Course::published()
            ->with(['category', 'user', 'reviews', 'modules.lessons'])
            ->where('user_id', '!=', $user->id)
            ->when(
                $enrolledCourseIds->isNotEmpty(),
                fn ($query) => $query->whereNotIn('id', $enrolledCourseIds->all())
            )
            ->latest();

        $recommendations = collect();

        if ($preferredCategoryIds->isNotEmpty()) {
            $recommendations = $baseQuery()
                ->whereIn('category_id', $preferredCategoryIds->all())
                ->take(3)
                ->get();
        }

        if ($recommendations->count() < 3) {
            $fallbackRecommendations = $baseQuery()
                ->when(
                    $recommendations->isNotEmpty(),
                    fn ($query) => $query->whereNotIn('id', $recommendations->pluck('id')->all())
                )
                ->take(3 - $recommendations->count())
                ->get();

            $recommendations = $recommendations->concat($fallbackRecommendations);
        }

        return $recommendations
            ->unique('id')
            ->values()
            ->map(fn (Course $course) => $this->makeRecommendationSnapshot($course));
    }

    private function makeStudentCourseSnapshot(Enrollment $enrollment): array
    {
        $course = $enrollment->course;
        $modules = $course->modules->values();
        $lessonTimeline = $modules
            ->flatMap(function ($module) {
                return $module->lessons->values()->map(fn ($lesson) => [
                    'lesson' => $lesson,
                    'module' => $module,
                ]);
            })
            ->values();

        $progress = (int) $enrollment->progress;
        $totalLessons = $lessonTimeline->count();
        $totalMinutes = (int) $modules->sum('duration')
            + (int) $lessonTimeline->sum(fn (array $item) => (int) $item['lesson']->duration);
        $nextLessonItem = null;

        if ($totalLessons > 0 && $progress < 100) {
            $nextLessonIndex = (int) floor(($progress / 100) * $totalLessons);
            $nextLessonIndex = max(0, min($totalLessons - 1, $nextLessonIndex));
            $nextLessonItem = $lessonTimeline->get($nextLessonIndex);
        }

        $averageRating = round((float) $course->reviews->avg('rating'), 1);

        return [
            'enrollment' => $enrollment,
            'course' => $course,
            'progress' => $progress,
            'isCompleted' => $progress >= 100,
            'totalModules' => $modules->count(),
            'totalLessons' => $totalLessons,
            'totalMinutes' => $totalMinutes,
            'remainingMinutes' => max(0, (int) round($totalMinutes * ((100 - $progress) / 100))),
            'thumbnailUrl' => $this->resolveMediaUrl($course->thumbnail),
            'categoryName' => $course->category?->name ?? 'General',
            'mentorName' => $course->user?->name ?? 'Course Instructor',
            'averageRating' => $averageRating > 0 ? number_format($averageRating, 1) : null,
            'reviewCount' => $course->reviews->count(),
            'nextLesson' => $nextLessonItem['lesson'] ?? null,
            'nextModule' => $nextLessonItem['module'] ?? null,
        ];
    }

    private function makeRecommendationSnapshot(Course $course): array
    {
        $modules = $course->modules->values();
        $lessons = $modules->flatMap->lessons->values();
        $averageRating = round((float) $course->reviews->avg('rating'), 1);

        return [
            'course' => $course,
            'thumbnailUrl' => $this->resolveMediaUrl($course->thumbnail),
            'categoryName' => $course->category?->name ?? 'General',
            'mentorName' => $course->user?->name ?? 'Course Instructor',
            'totalModules' => $modules->count(),
            'totalLessons' => $lessons->count(),
            'totalMinutes' => (int) $modules->sum('duration') + (int) $lessons->sum('duration'),
            'averageRating' => $averageRating > 0 ? number_format($averageRating, 1) : null,
            'reviewCount' => $course->reviews->count(),
        ];
    }

    private function resolveMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }
 
    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'host'      => redirect()->route('host.dashboard'),
            'student'   => redirect()->route('student.dashboard'),
            'visitor'   => redirect()->route('visitor.dashboard'),
            default     => redirect()->route('dashboard'),
        };
    }
}
