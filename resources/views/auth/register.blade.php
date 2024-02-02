@extends('layouts.auth')

@section('title', 'Register')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('stisla/library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Daftar Akun</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input id="name"
                        type="text"
                        value="{{ old('name') }}"
                        class="form-control"
                        name="name"
                        autofocus>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email"
                        type="email"
                        value="{{ old('email') }}"
                        class="form-control"
                        name="email"
                        required>
                    <div class="invalid-feedback">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-6">
                        <label for="password"
                            class="d-block">Password</label>
                        <input id="password"
                            type="password"
                            autocomplete="new-password"
                            class="form-control pwstrength"
                            data-indicator="pwindicator"
                            name="password"
                            required>
                        <div id="pwindicator"
                            class="pwindicator">
                            <div class="bar"></div>
                            <div class="label"></div>
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="password_confirmation"
                            class="d-block">Konfirmasi Password</label>
                        <input id="password_confirmation"
                            type="password"
                            class="form-control"
                            autocomplete="new-password"
                            name="password_confirmation"
                            required>
                            @if($errors->has('password'))
                                <span style="color: red">{{ $errors->first('password') }}</span>
                            @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                            name="agree"
                            class="custom-control-input"
                            id="agree">
                        <label class="custom-control-label"
                            for="agree">Saya setuju dengan syarat dan ketentuan</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit"
                        class="btn btn-primary btn-lg btn-block">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="text-muted mt-5 text-center">
        Sudah memiliki akun? <a href="{{ route('login') }}">Login sekarang</a>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('stisla/library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('stisla/library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('stisla/js/page/auth-register.js') }}"></script>
@endpush
