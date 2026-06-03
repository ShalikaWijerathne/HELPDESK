@extends('layouts.app')

@section('title', 'New Ticket')
@section('page-title', 'Submit a Ticket')

@section('content')

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card m-b-20">
            <div class="card-body">

                <h4 class="card-title mb-4">New Support Ticket</h4>

                <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                    @csrf

                    @if(auth()->user()->isStaffOrAdmin() && $users->isNotEmpty())
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Requester</label>
                            <div class="col-sm-9">
                                <select name="requester_id" class="form-control">
                                    <option value="">— Raise for myself —</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ old('requester_id') == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }} ({{ $u->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Select a user to raise this ticket on their behalf.</small>
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Subject <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="subject"
                                   class="form-control @error('subject') is-invalid @enderror"
                                   value="{{ old('subject') }}" placeholder="Brief description of the issue" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Description <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea name="description" rows="6"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Describe your issue in detail..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Category <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                <option value="">Select category...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Priority <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select name="priority_id" class="form-control @error('priority_id') is-invalid @enderror" required>
                                <option value="">Select priority...</option>
                                @foreach($priorities as $pri)
                                    <option value="{{ $pri->id }}" {{ old('priority_id') == $pri->id ? 'selected' : '' }}>
                                        {{ $pri->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('priority_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Attachment</label>
                        <div class="col-sm-9">
                            <input type="file" name="attachment"
                                   class="form-control @error('attachment') is-invalid @enderror"
                                   accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
                            <small class="text-muted">Optional. Max 10 MB. Allowed: jpg, png, pdf, doc, xls, txt, zip.</small>
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-2">
                                <i class="fa fa-paper-plane"></i> Submit Ticket
                            </button>
                            <a href="{{ route('tickets.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
