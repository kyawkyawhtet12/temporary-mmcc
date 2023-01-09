@extends('auth.layouts.master')

@section('content')
<div class="auth-page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mt-sm-5 mb-4 text-white-50">
                    <div>
                        <a href="{{ url('/') }}" class="d-inline-block auth-logo">
                            <h3 class='text-white'>  </h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4">
                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <h5 class="text-primary">Welcome Back !</h5>
                            <p class="text-muted">
                                Sign in to {{ isset($url) ? 'Agent' : 'Admin' }} Dashboard.
                            </p>
                        </div>
                        <div class="p-2 mt-4">

                            @isset($url)
                                <?php $route = route('agent.login') ?>
                            @else
                                <?php $route = route('admin.login') ?>
                            @endisset

                            <form method="POST" action="{{ $route }}" aria-label="{{ __('Login') }}">

                                @csrf
                                
                                @isset($url)

                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('Phone Number') }}</label>                                    
                                    <div class="col-md-12">
                                        <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autofocus>

                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                @else

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <div class="col-md-12">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                @endif

                                <div class="mb-3">
                                    <div class="float-end">
                                        <a href="auth-pass-reset-basic.html" class="text-muted">Forgot password?</a>
                                    </div>
                                    <label class="form-label" for="password-input">Password</label>
                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                        {{-- <input type="password" class="form-control pe-5" placeholder="Enter password" id="password-input"> --}}
                                        <input type="password" class="form-control pe-5 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter password" id="password-input">
                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>

                                {{-- <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                    <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                </div> --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>

                                <div class="mt-4">
                                    
                                    <button type="submit" class="btn btn-success w-100">
                                        {{ __('Login') }}
                                    </button>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                

            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
@endsection
