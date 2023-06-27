<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm"> 2D/3D </span>
            <span class="logo-lg"> 2D/3D </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm"> 2D/3D </span>
            <span class="logo-lg"> 2D/3D </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>

            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Admin Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('/dashboard') }}">
                        <i class="ri-information-line"></i> <span data-key="t-payment-accounts"> Dashboard </span>
                    </a>
                </li>

                {{--  2D --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#two-digits-number-all" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="two-digits-number-all">
                        <i class="ri-award-line"></i> <span data-key="t-apps"> 2D Number </span>
                    </a>
                    <div class="collapse menu-dropdown" id="two-digits-number-all">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('twodigits.today-report') }}" class="nav-link" data-key="t-calendar">
                                    Today Report </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('two-digits.result') }}" class="nav-link" data-key="t-2d-result">
                                    2D Result </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('2d.disable') }}" class="nav-link">
                                    2D Open/Close
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/two_lucky_numbers') }}" class="nav-link"
                                    data-key="t-calendar"> Lucky Numbers </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/two_winners') }}" class="nav-link"
                                    data-key="t-calendar"> Lucky Winners </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('lottery-time.index') }}" class="nav-link"
                                    data-key="t-calendar"> Lottery Times </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('2d.limit.amount') }}" class="nav-link"
                                    data-key="t-calendar"> Limit Amount </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('2d.compensate.amount') }}" class="nav-link"
                                    data-key="t-calendar"> Limit Compensate </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{--  3D --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#three-digits-number-all" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="three-digits-number-all">
                        <i class="ri-award-line"></i> <span data-key="t-apps"> 3D Number </span>
                    </a>
                    <div class="collapse menu-dropdown" id="three-digits-number-all">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('threedigits.monthly-report') }}" class="nav-link" data-key="t-calendar">
                                    Monthly Report </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('three-digits.result') }}" class="nav-link"
                                    data-key="t-3d-result">
                                    3D Result </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('3d.disable') }}" class="nav-link">
                                    3D Open/Close
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/three_lucky_numbers') }}" class="nav-link"
                                    data-key="t-calendar"> Lucky Numbers
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/three_winners') }}" class="nav-link"
                                    data-key="t-calendar"> Lucky Winners
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('three-lottery-close.index') }}" class="nav-link"
                                    data-key="t-lucky-draws"> 3D Lottery Setting </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('3d.limit.amount') }}" class="nav-link"
                                    data-key="t-calendar"> Limit Amount
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('3d.compensate.amount') }}" class="nav-link"
                                    data-key="t-calendar"> Limit Compensate
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Ballone --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#ballone" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="ballone">
                        <i class="ri-football-line"></i> <span data-key="t-ballone"> Ballone </span>
                    </a>
                    <div class="collapse menu-dropdown" id="ballone">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('ballone.league.index') }}" class="nav-link"
                                    data-key="t-ballone-league"> League </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ballone.club.index') }}" class="nav-link"
                                    data-key="t-ballone-club"> Club </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{ route('ballone.match.index') }}" class="nav-link"
                                    data-key="t-ballone-match"> Match </a>
                            </li> --}}
                            <li class="nav-item">
                                <a href="{{ route('ballone.body.index') }}" class="nav-link"
                                    data-key="t-ballone-body"> Body Fees </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ballone.maung.index') }}" class="nav-link"
                                    data-key="t-ballone-maung"> Maung Fees </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{ route('ballone.match.history') }}" class="nav-link"
                                    data-key="t-ballone-match"> Match History</a>
                            </li> --}}
                            <li class="nav-item">
                                <a href="{{ route('ballone.match.refund.history') }}" class="nav-link"
                                    data-key="t-ballone-match"> Refund Match History</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('ballone.maung-limit.index') }}" class="nav-link"
                                    data-key="t-maung-limit"> Maung Limit </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ballone.maung-za.index') }}" class="nav-link"
                                    data-key="t-maung-za"> Maung Za </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('ballone.body-setting.index') }}" class="nav-link"
                                    data-key="t-body-setting"> Body Setting</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('staff.index') }}">
                        <i class="ri-user-2-line"></i> <span data-key="t-staffs">
                            Staffs </span>
                    </a>
                </li>

                {{-- Agent --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#agents" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="agents">
                        <i class="ri-user-2-line"></i> <span data-key="t-apps">Agents</span>
                    </a>
                    <div class="collapse menu-dropdown" id="agents">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('agents.index') }}" class="nav-link" data-key="t-agent-lists">
                                    Agent Lists </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('agent.payment-reports') }}" class="nav-link"
                                    data-key="t-agent-payment-reports">
                                    Payment Reports </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('agents.create') }}" class="nav-link" data-key="t-two-lucky-draw">
                                    Two Lucky Draw </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('agents.three') }}" class="nav-link"
                                    data-key="t-three-lucky-draw"> Three Lucky Draw </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('agents.football') }}" class="nav-link"
                                    data-key="t-football-lucky-draw"> Football Lucky Draw </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('agent-deposit.index') }}" class="nav-link"
                                    data-key="t-agent-deposit">Agent Deposits </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('agent-withdraw.index') }}" class="nav-link"
                                    data-key="t-agent-deposit">Agent Withdraws </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- User --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('users.index') }}">
                        <i class="ri-user-2-line"></i> <span data-key="t-users">User Lists</span>
                    </a>
                </li>

                {{-- Payment Accoounts --}}

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('providers.index') }}">
                        <i class="ri-bank-card-line"></i> <span data-key="t-payment-accounts">
                            Payment Accounts</span>
                    </a>
                </li>

                {{-- Setting --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('banner.index') }}">
                        <i class="ri-image-line"></i> <span data-key="t-banner-images">
                            Banner Image
                        </span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>
