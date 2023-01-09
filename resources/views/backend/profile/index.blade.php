@extends('layouts.master')

@section('content')

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Profile</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">
                                Home </a></li>
                            <li class="breadcrumb-item active"> Profile </li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">

                @include('partials.msg')

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0 flex-grow-1"> Profile </h4>
                    </div>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label for="name" class="form-label">
                                        Name
                                    </label>
                                </div>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" readonly>
                                </div>
                            </div>

                            @if( Auth::guard('agent')->check())

                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label for="phone" class="form-label">
                                        Phone
                                    </label>
                                </div>
                                <div class="col-lg-9">
                                    <input type="phone" class="form-control" id="phone" name="phone" value="{{ $user->phone }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label for="referral_code" class="form-label">
                                        Referral Code
                                    </label>
                                </div>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" id="referral_code" name="referral_code" value="{{ $user->referral_code }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label for="amount" class="form-label">
                                        Current Amount
                                    </label>
                                </div>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" id="amount" name="amount" value="{{ $user->amount }}" readonly>
                                </div>
                            </div>

                            @else

                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label for="email" class="form-label">
                                        Email
                                    </label>
                                </div>
                                <div class="col-lg-9">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly>
                                </div>
                            </div>

                            @endif

                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label for="password" class="form-label">
                                        Password
                                    </label>
                                </div>
                                <div class="col-lg-9">
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label for="confirm-password" class="form-label">
                                        Confirm Password
                                    </label>
                                </div>
                                <div class="col-lg-9">
                                    <input type="password" class="form-control" id="confirm-password" name="confirm-password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-12 d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary btn-sm"> Submit </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection