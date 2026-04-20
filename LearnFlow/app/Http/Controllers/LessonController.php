<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
    public function store(StoreLessonRequest $request, Module $module)
    {
        $validated = $request->validated();

        $order = $module->lessons->max('order_number') + 1;

        $content = $this->resolveContent($request);

        $module->lessons()->create([
            'title'        => $validated['title'],
            'type'         => $validated['type'],
            'content'      => $content,
            'duration'     => $validated['duration'] ?? null,
            'is_free'      => $request->boolean('is_free'),
            'order_number' => $order,
        ]);
 
        return back()->with('success', 'Lesson created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        $lesson->load('module.course');
        $this->authorize('view', $lesson);

        $course = $lesson->module->course;

        return view('lessons.show', compact('lesson', 'course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        $this->authorize('update', $lesson);

        $data = $request->validated();
        $data['is_free'] = $request->boolean('is_free');
 
        $content = $this->resolveContent($request);
        if ($content) {
            $data['content'] = $content;
        }
 
        $lesson->update($data);
 
        return back()->with('success', 'Lesson updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $this->authorize('delete', $lesson);
 
        $lesson->delete();
 
        return back()->with('success', 'Lesson deleted successfully.');
    }

    public function reorder(Request $request, Module $module) {
        if (! Auth::user()->can('update', $module->course)) {
            abort(403);
        }
 
        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer', 'exists:lessons,id'],
        ]);
 
        $lessonIds = $module->lessons()->pluck('id')->map(fn ($id) => (int) $id)->sort()->values();
        $requestedIds = collect($request->order)->map(fn ($id) => (int) $id)->sort()->values();

        if ($lessonIds->count() !== $requestedIds->count() || $lessonIds->all() !== $requestedIds->all()) {
            return response()->json([
                'message' => 'The provided order must contain every lesson in this module exactly once.',
            ], 422);
        }

        foreach ($request->order as $position => $lessonId) {
            $module->lessons()->where('id', $lessonId)->update([
                'order_number' => $position + 1,
            ]);
        }
 
        return response()->json(['success' => true]);
    }

        private function resolveContent(Request $request): ?string
    {
        return match ($request->type) {
            'video'    => $request->content_url,
            'document' => $request->file('content_file')?->store('lessons/documents', 'public'),
            'text'     => $request->content_text,
            default    => null,
        };
    }
}
