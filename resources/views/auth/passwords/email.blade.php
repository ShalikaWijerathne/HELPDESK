<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — IT Help Desk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #2c3e50; min-height: 100vh; display: flex; align-items: center; }
        .card { border: none; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,.3); }
    </style>
</head>
<body>
<div class="container" style="max-width:440px">
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-3">Reset Password</h5>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-muted small">Back to login</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
