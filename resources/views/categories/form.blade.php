@extends('layouts.app')

@section('title', $category->exists ? 'Edit Category' : 'New Category')
@section('page-title', $category->exists ? 'Edit Category' : 'New Category')

@section('content')

<div class="row">
    <div class="col-lg-6 offset-lg-3">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title">{{ $category->exists ? 'Edit Category' : 'Create Category' }}</h4>

                <form method="POST"
                      action="{{ $category->exists ? route('categories.update', $category) : route('categories.store') }}">
                    @csrf
                    @if($category->exists) @method('PUT') @endif

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Name <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if($category->exists)
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox" class="custom-control-input" name="is_active" id="isActive" value="1"
                                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="isActive">Active</label>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-2">
                                <i class="fa fa-save"></i> Save
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
