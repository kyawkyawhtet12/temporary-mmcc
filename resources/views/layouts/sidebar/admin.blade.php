<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                2D/3D
            </span>
            <span class="logo-lg">
                2D/3D
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                2D/3D
            </span>
            <span class="logo-lg">
                2D/3D
            </span>
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

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('lottery.today-report') }}">
                        <i class="ri-information-line"></i> <span data-key="t-payment-accounts">
                            Lottery Today Report </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#ballone-today-report" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="ballone-today-report">
                        <i class="ri-award-line"></i> <span data-key="t-apps">Ballone Today Report</span>
                    </a>
                    <div class="collapse menu-dropdown" id="ballone-today-report">
                        <ul class="nav nav-sm flex-column">
                            {{-- <li class="nav-item">
                                <a href="{{ route('ballone.match-body-list') }}" class="nav-link" data-key="t-calendar">
                                    Body Match List </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ballone.match-maung-list') }}" class="nav-link"
                                    data-key="t-calendar">
                                    Maung Match List </a>
                            </li> --}}
                            <li class="nav-item">
                                <a href="{{ route('ballone.today-report') }}" class="nav-link" data-key="t-calendar">
                                    Today Report </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ballone.body.today-report') }}" class="nav-link"
                                    data-key="t-calendar"> Body Report </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ballone.maung.today-report') }}" class="nav-link"
                                    data-key="t-calendar"> Maung Report </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#lucky-numbers" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="lucky-numbers">
                        <i class="ri-coupon-line"></i> <span data-key="t-apps">Lucky Numbers</span>
                    </a>
                    <div class="collapse menu-dropdown" id="lucky-numbers">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ url('admin/two_lucky_numbers') }}" class="nav-link" data-key="t-calendar">
                                    2D Lucky Number </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/three_lucky_numbers') }}" class="nav-link" data-key="t-calendar">
                                    3D Lucky Number </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#lucky-winners" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="lucky-winners">
                        <i class="ri-award-line"></i> <span data-key="t-apps">Lucky Winners</span>
                    </a>
                    <div class="collapse menu-dropdown" id="lucky-winners">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ url('admin/two_winners') }}" class="nav-link" data-key="t-calendar"> 2D
                                    Digit Winners </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/three_winners') }}" class="nav-link" data-key="t-calendar">
                                    3D
                                    Digit Winners </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#lucky-draws" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="lucky-draws">
                        <i class="ri-file-list-line"></i> <span data-key="t-apps">Lucky Draws</span>
                    </a>
                    <div class="collapse menu-dropdown" id="lucky-draws">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ url('admin/two_lucky_draws') }}" class="nav-link"
                                    data-key="t-lucky-draws"> 2D Lucky Draw </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/three_lucky_draws') }}" class="nav-link"
                                    data-key="t-lucky-draws"> 3D Lucky Draw </a>
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
                            <li class="nav-item">
                                <a href="{{ route('ballone.match.index') }}" class="nav-link"
                                    data-key="t-ballone-match"> Match </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ballone.body.index') }}" class="nav-link"
                                    data-key="t-ballone-body"> Body Fees </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ballone.maung.index') }}" class="nav-link"
                                    data-key="t-ballone-maung"> Maung Fees </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ballone.match.history') }}" class="nav-link"
                                    data-key="t-ballone-match"> Match History</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ballone.match.refund.history') }}" class="nav-link"
                                    data-key="t-ballone-match"> Refund Match History</a>
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

                {{-- agent start --}}
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
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('agent-deposit.index') }}">
                        <i class="ri-money-euro-circle-line"></i> <span data-key="t-users">Agent Deposits</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('agent-withdraw.index') }}">
                        <i class="ri-file-download-line"></i> <span data-key="t-users">Agent Withdraws</span>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('agent.percentage') }}">
                        <i class="ri-percent-line"></i> <span data-key="t-users">Agent Percentage</span>
                    </a>
                </li> --}}
                {{-- agent end --}}

                {{-- User --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('users.index') }}">
                        <i class="ri-user-2-line"></i> <span data-key="t-users">User Lists</span>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('admin/user-payments') }}" >
                        <i class="ri-information-line"></i> <span data-key="t-users"> User Deposits </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('admin/cashouts') }}" >
                        <i class="ri-file-download-line"></i> <span data-key="t-users"> User Withdrawals </span>
                    </a>
                </li> --}}


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

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#system-setting" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="system-setting">
                        <i class="ri-settings-2-line"></i> <span data-key="t-apps">2D / 3D Setting</span>
                    </a>
                    <div class="collapse menu-dropdown" id="system-setting">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('lottery-time.index') }}" class="nav-link"
                                    data-key="t-lucky-draws"> 2D Lottery Times </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('three-lottery-close.index') }}" class="nav-link"
                                    data-key="t-lucky-draws"> 3D Lottery Setting </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('limit.amount') }}" class="nav-link" data-key="t-limit-amount">
                                    Limit Amount </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('compensate.amount') }}" class="nav-link"
                                    data-key="t-limit-compensate"> Limit Compensate </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#ballone-setting" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="ballone-setting">
                        <i class="ri-football-line"></i> <span data-key="t-ballone-setting"> Ballone Setting </span>
                    </a>
                    <div class="collapse menu-dropdown" id="ballone-setting">
                        <ul class="nav nav-sm flex-column">

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

            </ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>
