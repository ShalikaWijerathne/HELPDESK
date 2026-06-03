@extends('layouts.app')

@section('title', 'My Account')
@section('page-title', 'My Account')

@section('content')

<div class="row">

    {{-- Profile Details --}}
    <div class="col-lg-6">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title">Profile Information</h4>
                <p class="text-muted mb-4">Update your name and email address.</p>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PUT')

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Full Name</label>
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
                        <label class="col-sm-3 col-form-label">Email</label>
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
                        <label class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{ $user->role?->name }}" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                <i class="fa fa-save mr-1"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="col-lg-6">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title">Change Password</h4>
                <p class="text-muted mb-4">Ensure your account is using a strong password.</p>

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PUT')

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Current Password</label>
                        <div class="col-sm-8">
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="Enter current password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">New Password</label>
                        <div class="col-sm-8">
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min 8 characters">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Confirm Password</label>
                        <div class="col-sm-8">
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Repeat new password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-8 offset-sm-4">
                            <button type="submit" class="btn btn-warning waves-effect waves-light">
                                <i class="fa fa-lock mr-1"></i> Change Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
