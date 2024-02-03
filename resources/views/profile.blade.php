@php
$ada_jawaban = false;
@endphp
@extends('layouts.user.app')

@section('title')
{{ $user->name }}
@endsection

@section('content')
<main id="main">
    <div class="container mb-4" style="margin-top: 124px">
        <div class="row" style="justify-content:center">
            <div class="col-lg-4 mb-3">
                <div class="card mb-3">
                    <div class="card-body d-flex row">
                        <div class="col-lg-3 col-md-2">
                            <img alt="image" width="75px" height="75px" src="{{ $user->profile_photo_url }}" class="rounded-circle profile-widget-picture">
                        </div>
                        <div class="col-lg-9 col-md-10 align-self-center">
                            <span style="font-size: 20px" class="card-title" id="infoName">
                                <b>{{ $user->name }}</b>
                            </span>
                            <br>
                            <span style="font-size: 15px">
                                {{ $user->email }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Data Pendaftar</h5>
                        <form id="formPendaftar" action="{{ route('profile.pendaftar') }}" method="post">
                            @csrf
                            @method('post')
                            <div class="form-group required mb-2">
                                <label for="Prodi" class="col-form-label">Program Studi</label>
                                <select id="prodi" class="form-control select2" required name="prodi">
                                    <option value="" selected="selected">-- Cari Program Studi --</option>
                                    <option @if($user->usersDetail) {{ $user->usersDetail->prodi == 2 ? 'selected' : '' }} @endif value="2">D4 - Statistika</option>
                                    <option @if($user->usersDetail) {{ $user->usersDetail->prodi == 3 ? 'selected' : '' }} @endif value="3">D4 - Komputasi Statistik</option>
                                    <option @if($user->usersDetail) {{ $user->usersDetail->prodi == 1 ? 'selected' : '' }} @endif value="1">D3 - Statistika</option>
                                </select>
                                <div class="invalid-feedback">
                                    Kolom kabupaten/kota tidak boleh kosong.
                                </div>
                            </div>
                            <div class="form-group required mb-2">
                                <label for="Formasi" class="col-form-label">Formasi</label>
                                <select id="formasi" class="form-control select2" required name="formasi">
                                    <option value="" selected="selected">-- Cari formasi --</option>
                                </select>
                                <div class="invalid-feedback">
                                    Kolom formasi tidak boleh kosong.
                                </div>
                            </div>
                            <button type="submit" id="submitPendaftar" class="btn btn-primary mt-4 mb-0 float-end">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Data Akun</h5>
                        <form id="formAccount" action="{{ route('profile.account') }}" method="post">
                            @csrf
                            @method('post')
                            <div class="form-group required mb-2">
                                <label for="Name" class="col-form-label">Nama Lengkap</label>
                                <input type="text" value="{{ $user->name }}" name="name" id="Name" class="form-control" placeholder="Nama" required>
                                <div class="invalid-feedback">
                                    Kolom nama tidak boleh kosong.
                                </div>
                            </div>
                            <div class="form-group required mb-2">
                                <label for="Email" class="col-form-label">Email</label>
                                <input type="text" value="{{ $user->email }}" readonly name="email" id="Email" class="form-control" placeholder="Email" required>
                                <div class="invalid-feedback">
                                    Kolom email tidak boleh kosong.
                                </div>
                            </div>
                            <div class="form-group required mb-2">
                                <label for="No_hp" class="col-form-label">No HP</label>
                                <input type="text" value="{{ $user->usersDetail ? $user->usersDetail->no_hp : '' }}" name="no_hp" id="No_hp" class="form-control" placeholder="No Handphone" required>
                                <div class="invalid-feedback">
                                    Kolom No HP tidak boleh kosong.
                                </div>
                            </div>
                            <div class="form-group required mb-2">
                                <label for="sumber" class="col-form-label">Sumber Mendapatkan Tryout</label>
                                <select class="form-control sumber" id="Sumber" name="sumber[]" multiple="multiple" data-placeholder="--Pilih Sumber Informasi Mendapatkan Tryout--" style="width: 100%;">
                                    <option value="Instagram">Instagram</option>
                                    <option value="WhatsApp">WhatsApp</option>
                                    <option value="Email">Email</option>
                                    <option value="Internet">Internet</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <div class="invalid-feedback">
                                    Kolom No HP tidak boleh kosong.
                                </div>
                            </div>
                            <button type="submit" id="submitAccount" class="btn btn-primary mt-4 mb-0 float-end">Simpan</button>
                        </form>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Data Peserta</h5>
                        <form id="formPeserta" action="{{ route('profile.peserta') }}" method="post">
                            @csrf
                            @method('post')
                            <div class="form-group required mb-2">
                                <label for="Provinsi" class="col-form-label">Provinsi</label>
                                <select id="provinsi" class="form-control select2" required name="provinsi">
                                    <option value="" selected="selected">-- Cari provinsi --</option>
                                </select>
                                <div class="invalid-feedback">
                                    Kolom provinsi tidak boleh kosong.
                                </div>
                            </div>
                            <div class="form-group required mb-2">
                                <label for="Kabupaten" class="col-form-label">Kabupaten/Kota</label>
                                <select id="kabupaten" class="form-control select2" required name="kabupaten">
                                    <option value="" selected="selected">-- Cari kabupaten/kota --</option>
                                </select>
                                <div class="invalid-feedback">
                                    Kolom kabupaten/kota tidak boleh kosong.
                                </div>
                            </div>
                            <div class="form-group required mb-2">
                                <label for="Kecamatan" class="col-form-label">Kecamatan</label>
                                <select id="kecamatan" class="form-control select2" required name="kecamatan">
                                    <option value="" selected="selected">-- Cari Kecamatan --</option>
                                </select>
                                <div class="invalid-feedback">
                                    Kolom kecamatan tidak boleh kosong.
                                </div>
                            </div>
                            <div class="form-group required mb-2">
                                <label for="Asal_sekolah" class="col-form-label">Asal Sekolah</label>
                                <input type="text" value="{{ $user->usersDetail ? $user->usersDetail->asal_sekolah : '' }}" name="asal_sekolah" id="Asal_sekolah" class="form-control" placeholder="Asal Sekolah" required>
                                <div class="invalid-feedback">
                                    Kolom asal sekolah tidak boleh kosong.
                                </div>
                            </div>
                            <div class="form-group required mb-2">
                                <label for="Instagram" class="col-form-label">Instagram</label>
                                <input type="text" value="{{ $user->usersDetail ? $user->usersDetail->instagram : '' }}" name="instagram" id="Instagram" class="form-control" placeholder="Instagram" required>
                                <div class="invalid-feedback">
                                    Kolom instagram tidak boleh kosong.
                                </div>
                            </div>
                            <button type="submit" id="submitPeserta" class="btn btn-primary mt-4 mb-0 float-end">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    $(function(){

        $('#Sumber').select2({
            theme: 'bootstrap4',
        });

        $('#formasi').select2({
            theme: 'bootstrap4',
        });

        $('#prodi').select2({
            theme: 'bootstrap4',
        });

        @if ($user->usersDetail != NULL)
            let selectedSumber = @json($user->usersDetail->sumber_informasi);
            $('#Sumber').val(selectedSumber).change();
        @endif

        $('#formAccount').on('submit', function(e) {
            if (! e.preventDefault()) {
                let submitAccount = $('#submitAccount').html();

                $('#submitAccount').html('Loading <div class="spinner-border spinner-border-sm" role="status"></div>');
                $('#submitAccount').attr('type', 'button');
                $.post($('#formAccount').attr('action'), $('#formAccount').serialize())
                .done((response) => {
                    $('#submitAccount').html(submitAccount);
                    $('#submitAccount').attr('type', 'submit');
                    $('#infoName').html('<b>' + response.data.name + '</b>');
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.success(response.message);
                })
                .fail((errors) => {
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.error(errors);
                    return;
                });
                }
            }
        );

        @if ($user->usersDetail != NULL)
            @if ($user->usersDetail->provinsi != NULL && $user->usersDetail->kabupaten != NULL && $user->usersDetail->kecamatan != NULL)

            $.ajax({ // make the request for the selected data object
                type: 'GET',
                url: 'https://api.cahyadsn.com/province/' + '{{ $user->usersDetail->provinsi }}',
                dataType: 'json'
                }).then(function (res) {
                    // Here we should have the data object
                    $('#provinsi').append(`<option value="${res.data.kode}" selected="selected">${res.data.nama}</option>`);
            });

            $.ajax({ // make the request for the selected data object
                type: 'GET',
                url: 'https://api.cahyadsn.com/regency/' + '{{ $user->usersDetail->kabupaten }}',
                dataType: 'json'
                }).then(function (res) {
                    // Here we should have the data object
                    $('#kabupaten').append(`<option value="${res.data.kode}" selected="selected">${res.data.nama}</option>`);
            });

            $.ajax({ // make the request for the selected data object
                type: 'GET',
                url: 'https://api.cahyadsn.com/district/' + '{{ $user->usersDetail->kecamatan }}',
                dataType: 'json'
                }).then(function (res) {
                    // Here we should have the data object
                    $('#kecamatan').append(`<option value="${res.data.kode}" selected="selected">${res.data.nama}</option>`);
            });
            @endif
            @if ($user->usersDetail->penempatan != NULL)
                $.ajax({ // make the request for the selected data object
                    type: 'GET',
                    url: 'https://api.cahyadsn.com/province/' + '{{ $user->usersDetail->penempatan }}',
                    dataType: 'json'
                    }).then(function (res) {
                        // Here we should have the data object
                        $('#formasi').append(`<option value="${res.data.kode}" selected="selected">${res.data.nama}</option>`);
                });
            @endif
        @endif

        $('#provinsi').select2({
            theme: 'bootstrap4',
            minimumInputLength: 3,
            ajax: {
                url: function (params) {
                    return 'https://api.cahyadsn.com/search/' + params.term;
                },
                cache: false,
                type: "get",
                dataType: 'json',
                delay: 250,
                processResults: function (response) {
                    let res = [];
                    if (response.data != 'Data not found') {
                        res = response.data.map(item => {
                            return {
                                id: item.kode,
                                text: item.nama
                            };
                        });

                        res = res.filter(function (item) {
                            return item.id.length == 2
                        });
                    }
                    return {
                        results: res
                    };
                },
                cache: true
            }
        });

        $.ajax({
            url: 'https://api.cahyadsn.com/provinces',
            type: 'get',
            dataType: 'json',
            success: function (response){
                let res = [];
                if (response.data != 'Data not found') {
                    res = response.data.map(item => {
                        return {
                            id: item.kode,
                            text: item.nama
                        };
                    });
                }
                res.forEach(item => {
                    let newOption = new Option(item.text, item.id, false, false);
                    $('#formasi').append(newOption).trigger('change');
                });
            }
        });

        $('#provinsi').on("select2:selecting", function(e) {
            $('#kabupaten').append('<option value="" selected="selected">-- Cari kabupaten/kota --</option>');
            $('#kecamatan').append('<option value="" selected="selected">-- Cari kecamatan --</option>');
        });

        $('#kabupaten').on("select2:selecting", function(e) {
            $('#kecamatan').append('<option value="" selected="selected">-- Cari kecamatan --</option>');
        });

        $('#kabupaten').select2({
            theme: 'bootstrap4',
            minimumInputLength: 1,
            ajax: {
                url: function (params) {
                    return 'https://api.cahyadsn.com/search/' + params.term;
                },
                cache: false,
                type: "get",
                dataType: 'json',
                delay: 250,
                processResults: function (response) {
                    let res = [];
                    if (response.data != 'Data not found' && $('#provinsi').val()) {
                        res = response.data.map(item => {
                            return {
                                id: item.kode,
                                text: item.nama
                            };
                        });

                        res = res.filter(function (item) {
                            return item.id.length == 5 && item.id.substring(0,2) == $('#provinsi').val();
                        });
                    }
                    return {
                        results: res
                    };
                },
                cache: true
            }
        });

        $('#kecamatan').select2({
            theme: 'bootstrap4',
            minimumInputLength: 1,
            ajax: {
                url: function (params) {
                    return 'https://api.cahyadsn.com/search/' + params.term;
                },
                cache: false,
                type: "get",
                dataType: 'json',
                delay: 250,
                processResults: function (response) {
                    let res = [];
                    if (response.data != 'Data not found' && $('#provinsi').val() && $('#kabupaten').val()) {
                        res = response.data.map(item => {
                            return {
                                id: item.kode,
                                text: item.nama
                            };
                        });

                        res = res.filter(function (item) {
                            return item.id.length == 8 && item.id.substring(0,2) == $('#provinsi').val() && item.id.substring(0,5) == $('#kabupaten').val();
                        });
                    }
                    return {
                        results: res
                    };
                },
                cache: true
            }
        });

        $('#formPeserta').on('submit', function(e) {
            if (! e.preventDefault()) {
                let submitPeserta = $('#submitPeserta').html();

                $('#submitPeserta').html('Loading <div class="spinner-border spinner-border-sm" role="status"></div>');
                $('#submitPeserta').attr('type', 'button');
                $.post($('#formPeserta').attr('action'), $('#formPeserta').serialize())
                .done((response) => {
                    $('#submitPeserta').html(submitPeserta);
                    $('#submitPeserta').attr('type', 'submit');
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.success(response.message);
                })
                .fail((errors) => {
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.error(errors);
                    return;
                });
                }
            }
        );

        $('#formPendaftar').on('submit', function(e) {
            if (! e.preventDefault()) {
                let submitPendaftar = $('#submitPendaftar').html();

                $('#submitPendaftar').html('Loading <div class="spinner-border spinner-border-sm" role="status"></div>');
                $('#submitPendaftar').attr('type', 'button');
                $.post($('#formPendaftar').attr('action'), $('#formPendaftar').serialize())
                .done((response) => {
                    $('#submitPendaftar').html(submitPendaftar);
                    $('#submitPendaftar').attr('type', 'submit');
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.success(response.message);
                })
                .fail((errors) => {
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.error(errors);
                    return;
                });
                }
            }
        );
    });
</script>
@endpush
