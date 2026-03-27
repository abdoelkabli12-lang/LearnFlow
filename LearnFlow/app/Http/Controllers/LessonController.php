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
        if (! $module->course->isOwnedBy(Auth::user())){
            abort(403);
        }

                $request->validated();

        $order = $module->lessons->max('order_number') + 1;

        $content = $this->resolveContent($request);

                $module->lessons()->create([
            'title'        => $request->title,
            'type'         => $request->type,
            'content'      => $content,
            'duration'     => $request->duration,
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
        $course = $lesson->module->course;

                if (! $lesson->is_free) {
            if (! Auth::check()) {
                return redirect()->route('login');
            }
            
            $isEnrolled = $course->enrollments()->where('user_id', Auth::id())->exists();

            if (! $isEnrolled && ! $course->isOwnedBy(Auth::user()) && Auth::user()->role !== 'admin') {
                abort(403, 'You must be enrolled to access this lesson.');
            }
        }
                
        $lesson->load('module.course');
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
                if (! $lesson->module->course->isOwnedBy(Auth::user())) {
            abort(403);
        }
 
        $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'type'         => ['required', 'in:video,document,text'],
            'duration'     => ['nullable', 'integer', 'min:1'],
            'is_free'      => ['boolean'],
            'content_url'  => ['required_if:type,video', 'nullable', 'url'],
            'content_file' => ['required_if:type,document', 'nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'content_text' => ['required_if:type,text', 'nullable', 'string'],
        ]);
 
        $data = $request->only('title', 'type', 'duration');
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
        if (! $lesson->module->course->isOwnedBy(Auth::user())) {
            abort(403);
        }
 
        $lesson->delete();
 
        return back()->with('success', 'Lesson deleted successfully.');
    }

    public function reorder(Request $request, Module $module) {
                if (! $module->course->isOwnedBy(Auth::user())) {
            abort(403);
        }
 
        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer', 'exists:lessons,id'],
        ]);
 
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
