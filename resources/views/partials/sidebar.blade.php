<div class="left side-menu">

    <div class="topbar-left">
        <a href="{{ route('dashboard') }}" class="logo">
            <b style="font-size:1rem; color:#fff; font-weight:700;">IT Help Desk</b>
        </a>
    </div>

    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul>

                <li class="menu-title">Main</li>

                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="fa fa-area-chart"></i><span> Dashboard</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <a href="{{ route('profile.edit') }}" class="waves-effect">
                        <i class="fa fa-user"></i><span> My Account</span>
                    </a>
                </li>

                <li class="menu-title">Tickets</li>

                <li class="{{ request()->routeIs('tickets.index') ? 'active' : '' }}">
                    <a href="{{ route('tickets.index') }}" class="waves-effect">
                        <i class="fa fa-ticket"></i><span> All Tickets</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('tickets.create') }}" class="waves-effect">
                        <i class="fa fa-plus-circle"></i><span> New Ticket</span>
                    </a>
                </li>

                <li class="menu-title">Knowledge Base</li>

                <li class="{{ request()->routeIs('kb.*') ? 'active' : '' }}">
                    <a href="{{ route('kb.index') }}" class="waves-effect">
                        <i class="fa fa-book"></i><span> Browse Articles</span>
                    </a>
                </li>

                @if(auth()->user()->isStaffOrAdmin())
                    <li>
                        <a href="{{ route('kb.create') }}" class="waves-effect">
                            <i class="fa fa-pencil"></i><span> Write Article</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->isAdmin())

                    <li class="menu-title">Administration</li>

                    <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="waves-effect">
                            <i class="fa fa-users"></i><span> User Management</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <a href="{{ route('categories.index') }}" class="waves-effect">
                            <i class="fa fa-tags"></i><span> Categories</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('priorities.*') ? 'active' : '' }}">
                        <a href="{{ route('priorities.index') }}" class="waves-effect">
                            <i class="fa fa-flag"></i><span> Priorities</span>
                        </a>
                    </li>

                    <li class="menu-title">Reports</li>

                    <li class="{{ request()->routeIs('reports.tickets-per-user') ? 'active' : '' }}">
                        <a href="{{ route('reports.tickets-per-user') }}" class="waves-effect">
                            <i class="fa fa-bar-chart"></i><span> Tickets per User</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('reports.technician-performance') ? 'active' : '' }}">
                        <a href="{{ route('reports.technician-performance') }}" class="waves-effect">
                            <i class="fa fa-line-chart"></i><span> Technician Report</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('reports.problem-areas') ? 'active' : '' }}">
                        <a href="{{ route('reports.problem-areas') }}" class="waves-effect">
                            <i class="fa fa-pie-chart"></i><span> Problem Areas</span>
                        </a>
                    </li>

                    <li class="menu-title">System</li>

                    <li class="{{ request()->routeIs('audit.*') ? 'active' : '' }}">
                        <a href="{{ route('audit.index') }}" class="waves-effect">
                            <i class="fa fa-history"></i><span> Audit Log</span>
                        </a>
                    </li>

                @endif

            </ul>
        </div>
        <div class="clearfix"></div>
    </div>

</div>
