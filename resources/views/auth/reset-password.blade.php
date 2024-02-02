@extends('layouts.auth')

@section('title', 'Reset Password')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Reset Password</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email"
                        type="email"
                        value="{{ old('email', $request->email) }}"
                        class="form-control"
                        name="email"
                        tabindex="1"
                        required
                        autofocus>
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input id="password"
                        type="password"
                        class="form-control pwstrength"
                        data-indicator="pwindicator"
                        name="password"
                        tabindex="2"
                        required>
                    <div id="pwindicator"
                        class="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input id="password_confirmation"
                        type="password"
                        class="form-control"
                        name="password_confirmation"
                        tabindex="2"
                        required>
                </div>

                <div class="form-group">
                    <button type="submit"
                        class="btn btn-primary btn-lg btn-block"
                        tabindex="4">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('stisla/library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('stisla/js/page/auth-register.js') }}"></script>
@endpush
