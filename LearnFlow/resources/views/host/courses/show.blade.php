@extends('layouts.test-page')

@section('title', $course->title)
@section('lead', 'Build curriculum, arrange lessons, and manage publishing from a focused host workspace.')

@section('content')
    @php
        $totalDuration = $course->modules->sum('duration') + $course->modules->flatMap->lessons->sum('duration');
        $lessonTypes = ['video' => 'Video', 'document' => 'Document', 'text' => 'Text'];
    @endphp

    <section class="card">
        <div class="split">
            <div class="stack" style="gap: 0.45rem;">
                <span class="eyebrow">Course workspace</span>
                <h2>{{ $course->title }}</h2>
                <p class="meta">
                    <strong>Category:</strong> {{ $course->category?->name ?? 'Uncategorized' }}
                    · <strong>Host:</strong> {{ $course->user?->name ?? 'Unknown' }}
                    · <strong>Level:</strong> {{ $course->level }}
                    · <strong>Price:</strong> ${{ number_format((float) $course->price, 2) }}
                </p>
            </div>

            <div class="actions">
                <span class="pill">
                    <span class="status-dot {{ $course->is_published ? '' : 'draft' }}"></span>
                    {{ $course->is_published ? 'Published' : 'Draft preview' }}
                </span>
                <a class="button secondary" href="{{ route('host.courses.index') }}">Back to courses</a>
                <a class="button secondary" href="{{ route('courses.show', $course) }}">Open public view</a>
                <a class="button secondary" href="{{ route('host.courses.edit', $course) }}">Edit course</a>
                <form method="POST" action="{{ route('host.courses.publish', $course) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit">{{ $course->is_published ? 'Unpublish' : 'Publish course' }}</button>
                </form>
            </div>
        </div>

        <p style="margin-top: 1rem;">{{ $course->description }}</p>

        <div class="metric-grid" style="margin-top: 1.25rem;">
            <article class="metric-card">
                <span class="eyebrow">Modules</span>
                <strong>{{ $course->modules->count() }}</strong>
                <p class="meta">Structured sections currently attached to this course.</p>
            </article>
            <article class="metric-card">
                <span class="eyebrow">Lessons</span>
                <strong>{{ $course->lessons->count() }}</strong>
                <p class="meta">Combined lessons across every module.</p>
            </article>
            <article class="metric-card">
                <span class="eyebrow">Students</span>
                <strong>{{ $totalStudents }}</strong>
                <p class="meta">Current enrollment count from the course detail query.</p>
            </article>
            <article class="metric-card">
                <span class="eyebrow">Rating</span>
                <strong>{{ $averageRating ? number_format((float) $averageRating, 1) : 'N/A' }}</strong>
                <p class="meta">Average review score. Total duration: {{ $totalDuration ?: 0 }} min.</p>
            </article>
        </div>
    </section>

    <div class="grid">
        <section class="card">
            <div class="split">
                <div>
                    <span class="eyebrow">Quick actions</span>
                    <h2>Create a module</h2>
                </div>
                <span class="pill">POST {{ route('host.courses.modules.store', $course) }}</span>
            </div>

            <form class="stack" method="POST" action="{{ route('host.courses.modules.store', $course) }}" style="margin-top: 1rem;">
                @csrf

                <div class="field">
                    <label for="module_title">Module title</label>
                    <input id="module_title" type="text" name="title" value="{{ old('title') }}" placeholder="Module 1 - Foundations" required>
                </div>

                <div class="field">
                    <label for="module_duration">Duration in minutes</label>
                    <input id="module_duration" type="number" min="1" name="duration" value="{{ old('duration') }}" placeholder="45">
                </div>

                <div class="actions">
                    <button type="submit">Add module</button>
                </div>
            </form>
        </section>

        <section class="card">
            <div class="split">
                <div>
                    <span class="eyebrow">Reorder modules</span>
                    <h2>Drag-free ordering</h2>
                </div>
                <span class="helper">Use the arrows, then save.</span>
            </div>

            @if ($course->modules->count())
                <form class="stack" data-reorder-form data-endpoint="{{ route('host.courses.modules.reorder', $course) }}" data-method="PATCH">
                    @csrf
                    <div class="reorder-list" data-order-list="modules">
                        @foreach ($course->modules as $module)
                            <div class="order-item" data-order-item data-id="{{ $module->id }}">
                                <div>
                                    <strong>{{ $module->title }}</strong>
                                    <p class="meta">{{ $module->lessons->count() }} lesson(s) · {{ $module->duration ?: 'No' }} min</p>
                                </div>
                                <div class="order-handle">
                                    <span class="pill mono">#{{ $module->id }}</span>
                                    <button class="ghost-button" type="button" data-move="up">Up</button>
                                    <button class="ghost-button" type="button" data-move="down">Down</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="actions">
                        <button type="submit">Save module order</button>
                        <span class="helper" data-reorder-message></span>
                    </div>
                </form>
            @else
                <p style="margin-top: 1rem;">Add a module first and the reorder controls will appear here.</p>
            @endif
        </section>
    </div>

    <section class="card">
        <div class="split">
            <div>
                <span class="eyebrow">Build your curriculum</span>
                <h2>Modules and lessons</h2>
            </div>
            <span class="helper">Everything below is wired to your existing host routes.</span>
        </div>

        @if ($course->modules->count())
            <div class="module-stack" style="margin-top: 1.25rem;">
                @foreach ($course->modules as $module)
                    <article class="module-card">
                        <div class="split">
                            <div class="stack" style="gap: 0.35rem;">
                                <div class="actions">
                                    <span class="pill">Module {{ $module->order_number }}</span>
                                    <span class="pill mono">ID {{ $module->id }}</span>
                                </div>
                                <h3>{{ $module->title }}</h3>
                                <p class="meta">{{ $module->duration ? $module->duration . ' min' : 'No module duration set' }}</p>
                            </div>

                            <div class="actions">
                                <a class="button secondary" href="{{ route('courses.show', $course) }}#module-{{ $module->id }}">Preview publicly</a>
                            </div>
                        </div>

                        <div class="grid" style="margin-top: 1rem;">
                            <section class="soft-panel">
                                <span class="eyebrow">Update module</span>
                                <form class="stack" method="POST" action="{{ route('host.courses.modules.update', [$course, $module]) }}" style="margin-top: 0.85rem;">
                                    @csrf
                                    @method('PATCH')

                                    <div class="field">
                                        <label for="module-title-{{ $module->id }}">Title</label>
                                        <input id="module-title-{{ $module->id }}" type="text" name="title" value="{{ old('title', $module->title) }}" required>
                                    </div>

                                    <div class="field">
                                        <label for="module-duration-{{ $module->id }}">Duration</label>
                                        <input id="module-duration-{{ $module->id }}" type="number" min="1" name="duration" value="{{ old('duration', $module->duration) }}">
                                    </div>

                                    <div class="actions">
                                        <button type="submit">Save module</button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('host.courses.modules.destroy', [$course, $module]) }}" style="margin-top: 0.85rem;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="danger" type="submit">Delete module</button>
                                </form>
                            </section>

                            <section class="soft-panel">
                                <div class="split">
                                    <div>
                                        <span class="eyebrow">Add lesson</span>
                                        <p class="meta">Create the next lesson for the selected module.</p>
                                    </div>
                                    <span class="pill">Module {{ $module->id }}</span>
                                </div>

                                <form class="stack lesson-form" method="POST" action="{{ route('host.lessons.store', $module) }}" enctype="multipart/form-data" style="margin-top: 0.85rem;">
                                    @csrf

                                    <div class="field">
                                        <label for="lesson-title-{{ $module->id }}">Title</label>
                                        <input id="lesson-title-{{ $module->id }}" type="text" name="title" placeholder="Lesson title" required>
                                    </div>

                                    <div class="field">
                                        <label for="lesson-type-{{ $module->id }}">Type</label>
                                        <select id="lesson-type-{{ $module->id }}" name="type" data-lesson-type required>
                                            @foreach ($lessonTypes as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="field">
                                        <label for="lesson-duration-{{ $module->id }}">Duration in minutes</label>
                                        <input id="lesson-duration-{{ $module->id }}" type="number" min="1" name="duration" placeholder="12">
                                    </div>

                                    <label style="display: flex; gap: 0.65rem; align-items: center;">
                                        <input type="checkbox" name="is_free" value="1" style="width: auto;">
                                        Mark as free preview
                                    </label>

                                    <div class="field content-panel is-active" data-content-panel="video">
                                        <label for="lesson-video-{{ $module->id }}">Video URL</label>
                                        <input id="lesson-video-{{ $module->id }}" type="url" name="content_url" placeholder="https://...">
                                    </div>

                                    <div class="field content-panel" data-content-panel="document">
                                        <label for="lesson-document-{{ $module->id }}">Document file</label>
                                        <input id="lesson-document-{{ $module->id }}" type="file" name="content_file" accept=".pdf,.doc,.docx">
                                    </div>

                                    <div class="field content-panel" data-content-panel="text">
                                        <label for="lesson-text-{{ $module->id }}">Text content</label>
                                        <textarea id="lesson-text-{{ $module->id }}" name="content_text" placeholder="Lesson notes or transcript"></textarea>
                                    </div>

                                    <div class="actions">
                                        <button type="submit">Create lesson</button>
                                    </div>
                                </form>
                            </section>
                        </div>

                        <section class="soft-panel" style="margin-top: 1rem;">
                            <div class="split">
                                <div>
                                    <span class="eyebrow">Lesson order</span>
                                    <h3>{{ $module->lessons->count() ? 'Adjust lesson sequence' : 'No lessons yet' }}</h3>
                                </div>
                                @if ($module->lessons->count())
                                    <span class="helper">Move lessons, then save the order for this module.</span>
                                @endif
                            </div>

                            @if ($module->lessons->count())
                                <form class="stack" data-reorder-form data-endpoint="{{ route('host.lessons.reorder', $module) }}" data-method="POST" style="margin-top: 0.85rem;">
                                    @csrf
                                    <div class="reorder-list" data-order-list="lessons">
                                        @foreach ($module->lessons as $lesson)
                                            <div class="order-item" data-order-item data-id="{{ $lesson->id }}">
                                                <div>
                                                    <strong>{{ $lesson->title }}</strong>
                                                    <p class="meta">{{ ucfirst($lesson->type) }} · {{ $lesson->duration ?: 'No' }} min · {{ $lesson->is_free ? 'Free' : 'Locked' }}</p>
                                                </div>
                                                <div class="order-handle">
                                                    <span class="pill mono">#{{ $lesson->id }}</span>
                                                    <button class="ghost-button" type="button" data-move="up">Up</button>
                                                    <button class="ghost-button" type="button" data-move="down">Down</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="actions">
                                        <button type="submit">Save lesson order</button>
                                        <span class="helper" data-reorder-message></span>
                                    </div>
                                </form>
                            @endif
                        </section>

                        <div class="lesson-stack" style="margin-top: 1rem;">
                            @forelse ($module->lessons as $lesson)
                                <article class="lesson-card" id="lesson-{{ $lesson->id }}">
                                    <div class="split">
                                        <div class="stack" style="gap: 0.35rem;">
                                            <div class="actions">
                                                <span class="pill">Lesson {{ $lesson->order_number }}</span>
                                                <span class="pill">{{ ucfirst($lesson->type) }}</span>
                                                @if ($lesson->is_free)
                                                    <span class="pill">Free preview</span>
                                                @endif
                                            </div>
                                            <h3>{{ $lesson->title }}</h3>
                                            <p class="meta">Duration: {{ $lesson->duration ? $lesson->duration . ' min' : 'Not set' }}</p>
                                        </div>

                                        <div class="actions">
                                            <a class="button secondary" href="{{ route('lessons.show', $lesson) }}">Open lesson</a>
                                        </div>
                                    </div>

                                    <form class="stack lesson-form" method="POST" action="{{ route('host.lessons.update', $lesson) }}" enctype="multipart/form-data" style="margin-top: 1rem;">
                                        @csrf
                                        @method('PUT')

                                        <div class="grid">
                                            <div class="field">
                                                <label for="lesson-edit-title-{{ $lesson->id }}">Title</label>
                                                <input id="lesson-edit-title-{{ $lesson->id }}" type="text" name="title" value="{{ $lesson->title }}" required>
                                            </div>

                                            <div class="field">
                                                <label for="lesson-edit-type-{{ $lesson->id }}">Type</label>
                                                <select id="lesson-edit-type-{{ $lesson->id }}" name="type" data-lesson-type required>
                                                    @foreach ($lessonTypes as $value => $label)
                                                        <option value="{{ $value }}" @selected($lesson->type === $value)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="grid">
                                            <div class="field">
                                                <label for="lesson-edit-duration-{{ $lesson->id }}">Duration in minutes</label>
                                                <input id="lesson-edit-duration-{{ $lesson->id }}" type="number" min="1" name="duration" value="{{ $lesson->duration }}">
                                            </div>

                                            <label style="display: flex; gap: 0.65rem; align-items: center; margin-top: 1.9rem;">
                                                <input type="checkbox" name="is_free" value="1" style="width: auto;" @checked($lesson->is_free)>
                                                Mark as free preview
                                            </label>
                                        </div>

                                        <div class="field content-panel {{ $lesson->type === 'video' ? 'is-active' : '' }}" data-content-panel="video">
                                            <label for="lesson-edit-video-{{ $lesson->id }}">Video URL</label>
                                            <input id="lesson-edit-video-{{ $lesson->id }}" type="url" name="content_url" value="{{ $lesson->type === 'video' ? $lesson->content : '' }}" placeholder="https://...">
                                        </div>

                                        <div class="field content-panel {{ $lesson->type === 'document' ? 'is-active' : '' }}" data-content-panel="document">
                                            <label for="lesson-edit-document-{{ $lesson->id }}">Replace document</label>
                                            <input id="lesson-edit-document-{{ $lesson->id }}" type="file" name="content_file" accept=".pdf,.doc,.docx">
                                            @if ($lesson->type === 'document')
                                                <p class="helper">Current file: {{ $lesson->content }}</p>
                                            @endif
                                        </div>

                                        <div class="field content-panel {{ $lesson->type === 'text' ? 'is-active' : '' }}" data-content-panel="text">
                                            <label for="lesson-edit-text-{{ $lesson->id }}">Text content</label>
                                            <textarea id="lesson-edit-text-{{ $lesson->id }}" name="content_text" placeholder="Lesson notes or transcript">{{ $lesson->type === 'text' ? $lesson->content : '' }}</textarea>
                                        </div>

                                        <div class="actions">
                                            <button type="submit">Save lesson</button>
                                        </div>
                                    </form>

                                    <form method="POST" action="{{ route('host.lessons.destroy', $lesson) }}" style="margin-top: 0.85rem;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="danger" type="submit">Delete lesson</button>
                                    </form>
                                </article>
                            @empty
                                <p>No lessons yet in this module.</p>
                            @endforelse
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p style="margin-top: 1rem;">This course does not have any modules yet. Create one above and the full management workspace will expand around it.</p>
        @endif
    </section>

    <script>
        document.querySelectorAll('[data-move]').forEach((button) => {
            button.addEventListener('click', () => {
                const item = button.closest('[data-order-item]');
                const list = item?.parentElement;

                if (!item || !list) {
                    return;
                }

                if (button.dataset.move === 'up' && item.previousElementSibling) {
                    list.insertBefore(item, item.previousElementSibling);
                }

                if (button.dataset.move === 'down' && item.nextElementSibling) {
                    list.insertBefore(item.nextElementSibling, item);
                }
            });
        });

        document.querySelectorAll('[data-reorder-form]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const token = form.querySelector('input[name="_token"]')?.value;
                const method = form.dataset.method || 'POST';
                const endpoint = form.dataset.endpoint;
                const message = form.querySelector('[data-reorder-message]');
                const ids = Array.from(form.querySelectorAll('[data-order-item]')).map((item) => item.dataset.id);

                if (!token || !endpoint || !ids.length) {
                    return;
                }

                if (message) {
                    message.textContent = 'Saving order...';
                }

                try {
                    const response = await fetch(endpoint, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify({ order: ids }),
                    });

                    if (!response.ok) {
                        const data = await response.json().catch(() => ({}));
                        throw new Error(data.message || 'Unable to save the new order.');
                    }

                    if (message) {
                        message.textContent = 'Saved. Reloading...';
                    }

                    window.location.reload();
                } catch (error) {
                    if (message) {
                        message.textContent = error.message;
                    }
                }
            });
        });

        const setLessonPanels = (form) => {
            const typeField = form.querySelector('[data-lesson-type]');
            const panels = form.querySelectorAll('[data-content-panel]');

            if (!typeField || !panels.length) {
                return;
            }

            panels.forEach((panel) => {
                panel.classList.toggle('is-active', panel.dataset.contentPanel === typeField.value);
            });
        };

        document.querySelectorAll('.lesson-form').forEach((form) => {
            setLessonPanels(form);

            const typeField = form.querySelector('[data-lesson-type]');
            if (typeField) {
                typeField.addEventListener('change', () => setLessonPanels(form));
            }
        });
    </script>
@endsection
