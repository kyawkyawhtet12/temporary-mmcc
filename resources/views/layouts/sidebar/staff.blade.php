<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                {{-- <img src="{{asset('assets/images/logo-sm.png')}}" alt="" height="22"> --}}
                2D/3D
            </span>
            <span class="logo-lg">
                {{-- <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="17"> --}}
                2D/3D
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                {{-- <img src="{{asset('assets/images/logo-sm.png')}}" alt="" height="22"> --}}
                2D/3D
            </span>
            <span class="logo-lg">
                {{-- <img src="{{asset('assets/images/logo-light.png')}}" alt="" height="17"> --}}
                2D/3D
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Agent Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="/agent-dashboard">
                        <i class="ri-home-line"></i> <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('agent.users.index') }}" >
                        <i class="ri-user-2-line"></i> <span data-key="t-users">User Lists</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('agent.withdrawal-form') }}" >
                        <i class="ri-file-download-line"></i> <span data-key="t-users"> Withdrawal Form </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('agent.withdrawal-history') }}" >
                        <i class="ri-file-list-line"></i> <span data-key="t-users"> Withdrawal History </span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
