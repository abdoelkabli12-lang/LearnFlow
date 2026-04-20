<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\StoreModuleRequest;
use App\Http\Requests\UpdateModuleRequest;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    private function canManageCourse(Course $course): bool
    {
        return (bool) Auth::user()?->can('update', $course);
    }

    private function ensureModuleBelongsToCourse(Module $module, Course $course): void
    {
        if ($module->course_id !== $course->id) {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModuleRequest $request, Course $course)
    {
        if (! $this->canManageCourse($course)) {
            abort(403);
        }

        $validated = $request->validated();

        $order = $course->modules()->max('order_number') + 1;

        $course->modules()->create([
            'title' => $validated['title'],
            'order_number' => $order,
            'duration' => $validated['duration'] ?? null,
        ]);

        return back()->with('success', 'Module created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModuleRequest $request, Course $course, Module $module)
    {
        if (! $this->canManageCourse($course)) {
            abort(403);
        }

        $this->ensureModuleBelongsToCourse($module, $course);

        $module->update($request->validated());

        return back()->with('success', 'Module updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Module $module)
    {
        if (! $this->canManageCourse($course)) {
            abort(403);
        }

        $this->ensureModuleBelongsToCourse($module, $course);

        $module->delete();

        $course->modules()
            ->orderBy('order_number')
            ->get()
         
         
         
         
            ->each(function (Module $courseModule, int $index): void {
                $courseModule->update(['order_number' => $index + 1]);
            });

        return back()->with('success', 'Module deleted successfully');
    }

    public function reorder(Request $request, Course $course)
    {
        if (! $this->canManageCourse($course)) {
            abort(403);
        }

        $validated = $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer', 'distinct', 'exists:modules,id'],
        ]);

        $courseModuleIds = $course->modules()->pluck('id')->map(fn ($id) => (int) $id)->sort()->values();
        $requestedIds = collect($validated['order'])->map(fn ($id) => (int) $id)->sort()->values();

        if ($courseModuleIds->count() !== $requestedIds->count() || $courseModuleIds->all() !== $requestedIds->all()) {
            return response()->json([
                'message' => 'The provided order must contain every module in this course exactly once.',
            ], 422);
        }

        foreach ($validated['order'] as $position => $moduleId) {
            $course->modules()->where('id', $moduleId)->update([
                'order_number' => $position + 1,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
