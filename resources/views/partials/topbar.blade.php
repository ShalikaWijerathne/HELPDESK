<div class="topbar">
    <nav class="navbar-custom">

        <ul class="list-inline float-right mb-0">

            @php $unread = auth()->user()->unreadNotificationCount(); @endphp
            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link waves-effect" href="{{ route('notifications.index') }}">
                    <i class="mdi mdi-bell noti-icon"></i>
                    @if($unread > 0)
                        <span class="badge badge-danger noti-icon-badge">{{ $unread > 99 ? '99+' : $unread }}</span>
                    @endif
                </a>
            </li>

            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user"
                   data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <img src="{{ asset('assets/images/users/avatar-1.png') }}"
                         height="36" alt="user" class="rounded-circle mr-1">
                    <span class="d-none d-sm-inline-block">
                        {{ auth()->user()->name }}
                        <span class="badge badge-secondary ml-1">{{ auth()->user()->role?->name }}</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="dripicons-exit text-muted mr-1"></i> Logout
                    </a>
                </div>
            </li>

        </ul>

        <ul class="list-inline menu-left mb-0">
            <li class="list-inline-item">
                <button type="button" class="button-menu-mobile open-left waves-effect">
                    <i class="mdi mdi-menu"></i>
                </button>
            </li>
            <li class="list-inline-item">
                <h4 class="page-title">@yield('page-title', 'Dashboard')</h4>
            </li>
        </ul>

    </nav>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>
