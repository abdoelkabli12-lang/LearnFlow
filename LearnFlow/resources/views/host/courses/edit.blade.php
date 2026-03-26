@extends('layouts.test-page')

@section('title', 'Edit Course')
@section('lead', 'Minimal form for testing course updates and deletes.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Edit {{ $course->title }}</h2>
            <form class="stack" method="POST" action="{{ route('host.courses.update', $course) }}" enctype="multipart/form-data" style="margin-top: 1rem;">
                @csrf
                @method('PATCH')

                <div class="field">
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title" value="{{ old('title', $course->title) }}" required>
                </div>

                <div class="field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required>{{ old('description', $course->description) }}</textarea>
                </div>

                <div class="field">
                    <label for="price">Price</label>
                    <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $course->price) }}" required>
                </div>

                <div class="field">
                    <label for="level">Level</label>
                    <select id="level" name="level" required>
                        @foreach (['Beginner', 'Intermediate', 'Advanced', 'All'] as $level)
                            <option value="{{ $level }}" @selected(old('level', $course->level) === $level)>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id', $course->category_id) === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="thumbnail">Replace thumbnail</label>
                    <input id="thumbnail" type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp">
                </div>

                <div class="actions">
                    <button type="submit">Update course</button>
                    <a class="button secondary" href="{{ route('host.courses.show', $course) }}">Cancel</a>
                </div>
            </form>
        </section>

        <section class="card">
            <h2>Danger zone</h2>
            <form method="POST" action="{{ route('host.courses.destroy', $course) }}">
                @csrf
                @method('DELETE')
                <button class="danger" type="submit">Delete course</button>
            </form>
        </section>
    </div>
@endsection
