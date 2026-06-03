@extends('layouts.app')

@section('title', $article->exists ? 'Edit Article' : 'New Article')
@section('page-title', $article->exists ? 'Edit Article' : 'New KB Article')

@section('content')

<div class="row">
    <div class="col-lg-9 offset-lg-1">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title">{{ $article->exists ? 'Edit Article' : 'Write New Article' }}</h4>

                <form method="POST"
                      action="{{ $article->exists ? route('kb.update', $article) : route('kb.store') }}">
                    @csrf
                    @if($article->exists) @method('PUT') @endif

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Title <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $article->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Category</label>
                        <div class="col-sm-10">
                            <select name="category_id" class="form-control">
                                <option value="">— Uncategorised —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                            {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Body <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <textarea name="body" rows="14"
                                      class="form-control @error('body') is-invalid @enderror"
                                      placeholder="Write the article content here..." required>{{ old('body', $article->body) }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Published</label>
                        <div class="col-sm-10">
                            <div class="custom-control custom-checkbox mt-2">
                                <input type="checkbox" class="custom-control-input" name="is_published" id="isPublished" value="1"
                                       {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="isPublished">
                                    Make this article visible to all users
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-2">
                                <i class="fa fa-save"></i> Save Article
                            </button>
                            <a href="{{ route('kb.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
