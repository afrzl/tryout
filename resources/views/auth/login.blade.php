@extends('layouts.auth')

@section('title', 'Login')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('stisla/library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Login</h4>
        </div>

        <div class="card-body">
            <form method="POST"
                action="{{ route('login') }}"
                class="needs-validation"
                novalidate="">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email"
                        type="email"
                        :value="old('email')"
                        class="form-control"
                        name="email"
                        tabindex="1"
                        required
                        autofocus>
                    @if($errors->has('email'))
                        <span style="color: red">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password"
                            class="control-label">Password</label>
                        <div class="float-right">
                            <a href="{{ route('password.request') }}"
                                class="text-small">
                                Lupa password?
                            </a>
                        </div>
                    </div>
                    <input id="password"
                        type="password"
                        class="form-control"
                        name="password"
                        tabindex="2"
                        required>
                    @if($errors->has('password'))
                        <span style="color: red">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                            id="remember_me"
                            name="remember"
                            class="custom-control-input"
                            tabindex="3">
                        <label class="custom-control-label"
                            for="remember-me">Ingat saya</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit"
                        class="btn btn-primary btn-lg btn-block"
                        tabindex="4">
                        Login
                    </button>
                </div>
            </form>
            <div class="mt-4 mb-3 text-center">
                <div class="text-job text-muted">Login / Register dengan</div>
            </div>
            <div class="row sm-gutters">
                <div class="col-12">
                    <a href="{{ route('google.login') }}" type="button"
                        class="btn btn-danger btn-lg btn-block"
                        tabindex="4">
                        <span class="fab fa-google"></span> Google
                    </a>
                </div>
            </div>

        </div>
    </div>
    <div class="text-muted mt-5 text-center">
        Belum memiliki akun? <a href="{{ route('register') }}">Buat akun sekarang</a>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
