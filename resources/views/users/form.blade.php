@extends('layouts.app')

@section('title', $user->exists ? 'Edit User' : 'New User')
@section('page-title', $user->exists ? 'Edit User' : 'New User')

@section('content')

<div class="row">
    <div class="col-lg-7">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title">{{ $user->exists ? 'Edit User Account' : 'Create User Account' }}</h4>

                <form method="POST"
                      action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}">
                    @csrf
                    @if($user->exists) @method('PUT') @endif

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Full Name <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Role <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                                <option value="">Select a role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">
                            Password
                            @if(!$user->exists) <span class="text-danger">*</span> @endif
                        </label>
                        <div class="col-sm-9">
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="{{ $user->exists ? 'Leave blank to keep current password' : 'Min 8 characters' }}"
                                   {{ $user->exists ? '' : 'required' }}>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Confirm Password</label>
                        <div class="col-sm-9">
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Repeat password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-2">
                                <i class="fa fa-save mr-1"></i>
                                {{ $user->exists ? 'Save Changes' : 'Create User' }}
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
