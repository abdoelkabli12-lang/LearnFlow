@extends('layouts.test-page')

@section('title', 'Manage Courses')
@section('lead', 'Host and admin course management page for creating, editing, publishing, and deleting courses.')

@section('content')
    <section class="card">
        <div class="split">
            <h2>Your courses</h2>
            <a class="button" href="{{ route('host.courses.create') }}">Create course</a>
        </div>

        @if ($courses->count())
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->category?->name ?? 'Uncategorized' }}</td>
                            <td>{{ $course->level }}</td>
                            <td>{{ $course->is_published ? 'Published' : 'Draft' }}</td>
                            <td class="actions">
                                <a class="button secondary" href="{{ route('host.courses.show', $course) }}">Show</a>
                                <a class="button secondary" href="{{ route('host.courses.edit', $course) }}">Edit</a>
                                <form method="POST" action="{{ route('host.courses.publish', $course) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">{{ $course->is_published ? 'Unpublish' : 'Publish' }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="margin-top: 1rem;">No courses yet. Create your first one to test the full flow.</p>
        @endif
    </section>
@endsection
