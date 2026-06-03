@extends('layouts.app')

@section('title', $priority->exists ? 'Edit Priority' : 'New Priority')
@section('page-title', $priority->exists ? 'Edit Priority' : 'New Priority')

@section('content')

<div class="row">
    <div class="col-lg-6 offset-lg-3">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title">{{ $priority->exists ? 'Edit Priority' : 'Create Priority' }}</h4>

                <form method="POST"
                      action="{{ $priority->exists ? route('priorities.update', $priority) : route('priorities.store') }}">
                    @csrf
                    @if($priority->exists) @method('PUT') @endif

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Name <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $priority->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Rank <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" name="rank" min="1" max="100"
                                   class="form-control @error('rank') is-invalid @enderror"
                                   value="{{ old('rank', $priority->rank) }}" required>
                            <small class="text-muted">Higher number = higher urgency (e.g. Urgent = 4).</small>
                            @error('rank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-2">
                                <i class="fa fa-save"></i> Save
                            </button>
                            <a href="{{ route('priorities.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
