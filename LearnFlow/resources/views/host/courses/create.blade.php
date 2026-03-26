@extends('layouts.test-page')

@section('title', 'Create Course')
@section('lead', 'Minimal form for testing course creation as a host or admin.')

@section('content')
    <section class="card">
        <h2>Create a new course</h2>
        <form class="stack" method="POST" action="{{ route('host.courses.store') }}" enctype="multipart/form-data" style="margin-top: 1rem;">
            @csrf

            <div class="field">
                <label for="title">Title</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required>
            </div>

            <div class="field">
                <label for="description">Description</label>
                <textarea id="description" name="description" required>{{ old('description') }}</textarea>
            </div>

            <div class="field">
                <label for="price">Price</label>
                <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', 0) }}" required>
            </div>

            <div class="field">
                <label for="level">Level</label>
                <select id="level" name="level" required>
                    @foreach (['Beginner', 'Intermediate', 'Advanced', 'All'] as $level)
                        <option value="{{ $level }}" @selected(old('level') === $level)>{{ $level }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Choose a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="thumbnail">Thumbnail</label>
                <input id="thumbnail" type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp">
            </div>

            <div class="actions">
                <button type="submit">Create course</button>
                <a class="button secondary" href="{{ route('host.courses.index') }}">Back</a>
            </div>
        </form>
    </section>
@endsection
