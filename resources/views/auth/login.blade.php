<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Login — IT Help Desk</title>
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
</head>
<body>

<div id="preloader"><div id="status"><div class="spinner"></div></div></div>

<div class="accountbg" style="background: linear-gradient(to bottom, rgb(6, 75, 92) 0%, #4096ee 100%);"></div>

<div class="wrapper-page">

    <div class="card" style="background-color: rgba(245, 245, 245, 0.8);">
        <div class="card-body">

            <h3 class="text-center m-0">
                <a href="{{ route('login') }}" class="logo logo-admin">
                    <span style="font-size:1.4rem; font-weight:700; color:#064b5c;">IT Help Desk</span>
                </a>
            </h3>

            <div class="p-3">
                <h4 class="text-muted font-18 m-b-5 text-center">Welcome Back!</h4>
                <p class="text-muted text-center">Sign in to continue</p>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form class="form-horizontal m-t-30" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="{{ old('email') }}" placeholder="Enter email" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Enter password" required>
                    </div>

                    <div class="form-group row m-t-20">
                        <div class="col-sm-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="remember" id="remember">
                                <label class="custom-control-label" for="remember">Remember me</label>
                            </div>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Log In</button>
                        </div>
                    </div>

                </form>

                <div class="alert alert-info m-t-20" style="font-size:0.8rem;">
                    <strong>Demo accounts:</strong><br>
                    user@helpdesk.test / password<br>
                    staff@helpdesk.test / password<br>
                    admin@helpdesk.test / password
                </div>
            </div>

        </div>
    </div>

    <div class="m-t-40 text-center">
        <p>Don't have an account?
            <a href="{{ route('register') }}" class="font-500 font-14 text-white"> Register</a>
        </p>
    </div>

</div>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('assets/js/waves.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
