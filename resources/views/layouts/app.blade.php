<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IT Help Desk') — IT Help Desk</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <style>
        /* Push content below the fixed topbar (topbar is ~70px tall) */
        .content-page > .content { padding-top: 80px !important; }

        /* Sidebar toggle button — visible and vertically centred on all screens */
        .button-menu-mobile {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #4a4a4a;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0 15px;
            height: 70px;
        }
        .button-menu-mobile:focus { outline: none; }

        /* Align page title text to same vertical centre as the button */
        .navbar-custom .menu-left { display: flex; align-items: center; height: 70px; }
        .navbar-custom .menu-left .list-inline-item { display: flex; align-items: center; }
        .navbar-custom .menu-left h4.page-title { margin: 0; line-height: 1; font-size: 16px; }

        /* Topbar extends full width when sidebar is collapsed */
        #wrapper.enlarged .topbar { left: 0; }

        /* Fix sidebar background glitch on re-expand:
           The theme uses background-attachment:fixed which causes a white flash
           as the sidebar slides back in from margin-left:-100%.
           Changing to scroll fixes the gradient to the element, not the viewport. */
        .left.side-menu {
            background: radial-gradient(at 50% -20%, #004a43, #0a1832) !important;
            background-attachment: scroll !important;
        }

        /* Smooth animate sidebar slide, content expand, and topbar stretch */
        .left.side-menu { transition: margin-left 0.2s ease; }
        .content-page   { transition: margin-left 0.2s ease; }
        .topbar         { transition: left 0.2s ease; }
    </style>

    @stack('styles')
</head>

<body class="fixed-left">

<div id="preloader">
    <div id="status"><div class="spinner"></div></div>
</div>

<div id="wrapper">

    @include('partials.sidebar')

    <div class="content-page">
        <div class="content">

            @include('partials.topbar')

            <div class="container-fluid">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </div>

        </div>

        <footer class="footer">
            <p>&copy; {{ date('Y') }} IT Help Desk Ticketing System &mdash; University of Colombo School of Computing</p>
        </footer>

    </div>

</div>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('assets/js/waves.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('assets/js/jquery.scrollTo.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

@stack('scripts')
</body>
</html>
