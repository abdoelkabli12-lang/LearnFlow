@extends('layouts.test-page')

@section('title', 'Manage Categories')
@section('lead', 'Minimal category management page so category routes work and course creation has a clean place to manage options.')

@section('content')
    <div class="grid">
        <section class="card">
            <h2>Create category</h2>
            <form class="stack" method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <button type="submit">Create category</button>
            </form>
        </section>

        <section class="card">
            <h2>Existing categories</h2>
            @if ($categories->count())
                <div class="stack" style="margin-top: 1rem;">
                    @foreach ($categories as $category)
                        <article class="card" style="padding: 1rem;">
                            <p><strong>{{ $category->name }}</strong></p>
                            <p class="meta">Slug: {{ $category->slug }} | Courses: {{ $category->courses_count }}</p>
                            <form class="stack" method="POST" action="{{ route('admin.categories.update', $category) }}">
                                @csrf
                                @method('PUT')
                                <div class="field">
                                    <label for="category_{{ $category->id }}">Rename</label>
                                    <input id="category_{{ $category->id }}" type="text" name="name" value="{{ $category->name }}" required>
                                </div>
                                <div class="actions">
                                    <button type="submit">Update</button>
                                </div>
                            </form>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                @csrf
                                @method('DELETE')
                                <button class="danger" type="submit">Delete</button>
                            </form>
                        </article>
                    @endforeach
                </div>
            @else
                <p style="margin-top: 1rem;">No categories yet.</p>
            @endif
        </section>
    </div>
@endsection
