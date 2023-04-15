@extends('layouts/app')

@section('title')
Data Soal Ujian {{ $ujian->nama }}
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item"><a href="{{ route('admin.ujian.index') }}">Ujian</a></li>
<li class="breadcrumb-item active"><a href="{{ route('admin.ujian.soal.index', $ujian->id) }}">Soal {{ $ujian->nama }}</a></li>
@endsection

@push('links')
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/summernote/summernote-bs4.min.css">
@endpush

@section('content')
<script type="text/javascript">
    document.body.classList.add('sidebar-collapse');
</script>

@php
    if ($action == 'admin.ujian.soal.store') {
        $id_route = $ujian->id;
    } else {
        $id_route = $soal->id;
    }
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route($action, $id_route) }}" method="post" class="form-horizontal needs-validation" autocomplete="off" novalidate>
                        @csrf
                        @if($action == 'admin.soal.update')
                            @method('put')
                            @php $adaKunci = 0; @endphp
                            @foreach ($soal->jawaban as $key => $value)
                            @if($value->isKunci)
                            <input type="hidden" id="kunciJawaban" value="{{ $value->id }}" required name="kunci_jawaban">
                            @php $adaKunci = 1; @endphp
                            @endif
                            @endforeach
                            @if(!$adaKunci)
                            <input type="hidden" id="kunciJawaban" required name="kunci_jawaban">
                            @endif
                        @else
                            @method('post')
                            <input type="hidden" id="kunciJawaban" required name="kunci_jawaban">
                        @endif
                        <div id="form">
                            <div class="form-group">
                                <label for="soal" class="col-sm-3 col-form-label">Soal</label>
                                <textarea id="soal" autofocus hidden name="soal">{{ old('soal', $soal->soal) }}
                                </textarea>
                                <div class="invalid-feedback">
                                    Kolom soal tidak boleh kosong.
                                </div>
                            </div>
                            @if ($action == 'admin.ujian.soal.store')
                            <div id="pilihan_0-new">
                                <label for="pilihan[]" class="col-sm-3 col-form-label">Opsi Jawaban</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button type="button" id="jawaban_0-new" onclick="jadikanKunci('0-new')" class="btn btn-outline-warning hapus-pilihan"><i class="fa fa-key"></i></button>
                                    </div>
                                    <input type="hidden" id="id_pilihan_0-new" name="id_pilihan[]" value="0-new">
                                    <input type="text" value="{{ old('pilihan[]') }}" name="pilihan[]" id="Pilihan" class="form-control" required>
                                    <span class="input-group-append">
                                        <button type="button" id="hapus_0-new" onclick="hapusPilihan(0)" class="btn btn-outline-danger">X</button>
                                    </span>
                                    <div class="invalid-feedback">
                                        Kolom pilihan tidak boleh kosong.
                                    </div>
                                </div>
                            </div>
                            @else
                            @foreach ($soal->jawaban as $key => $item)
                            <div id="pilihan_{{ $item->id }}">
                                <label for="pilihan[]" class="col-sm-3 col-form-label">Opsi Jawaban</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button type="button" id="jawaban_{{ $item->id }}" onclick="jadikanKunci({{ $item->id }})" class="btn btn-outline-warning hapus-pilihan"><i class="fa fa-key"></i></button>
                                    </div>
                                    <input type="hidden" id="id_pilihan_{{ $item->id }}" name="id_pilihan[]" value="{{ $item->id }}">
                                    <input type="text" value="{{ old('pilihan[]', $item->jawaban) }}" name="pilihan[]" id="Pilihan" class="form-control" required>
                                    <span class="input-group-append">
                                        <button type="button" id="hapus_{{ $item->id }}" onclick="hapusPilihan({{ $item->id }})" class="btn btn-outline-danger">X</button>
                                    </span>
                                    <div class="invalid-feedback">
                                        Kolom pilihan tidak boleh kosong.
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <button type="button" id="btnTambahPilihan" onclick="tambahPilihan()" class="btn btn-outline-primary mt-3"><i class="fa fa-plus-circle"></i> Tambah Pilihan Jawaban</button>
                        <button type="submit" class="btn btn-outline-success mt-3 float-right">Save</button>
                    </form>
                    <!-- /.row -->
                </div>
                <!-- ./card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Main row -->

    <!-- /.row -->
</div>

@endsection

@push('scripts')
<script src="{{ asset('adminLTE') }}/plugins/summernote/summernote-bs4.min.js"></script>
<script type="text/javascript">
    let countPilihan = 1;
    document.body.classList.add('sidebar-collapse');

    $(function() {
        $('#soal').summernote({
            height: 250,
        });
        cek();
    })

    function tambahPilihan(){
        var form = document.getElementById("form");

        $(form).append(`<div id="pilihan_` + (countPilihan) + `-new">
                            <label for="pilihan[]" class="col-sm-3 col-form-label">Opsi Jawaban</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button type="button" id="jawaban_` + countPilihan + `-new" onclick="jadikanKunci('` + countPilihan + `-new')" class="btn btn-outline-warning hapus-pilihan"><i class="fa fa-key"></i></button>
                                </div>
                                <input type="hidden" id="id_pilihan_` + countPilihan + `-new" name="id_pilihan[]" value="` + countPilihan + `-new">
                                <input type="text" value="{{ old('pilihan[]') }}" name="pilihan[]" id="Pilihan" class="form-control" required>
                                <span class="input-group-append">
                                    <button type="button" id="hapus_` + countPilihan + `-new" onclick="hapusPilihan('` + countPilihan + `-new')" class="btn btn-outline-danger">X</button>
                                </span>
                                <div class="invalid-feedback">
                                    Kolom pilihan tidak boleh kosong.
                                </div>
                            </div>
                        </div>`); //add input box

        countPilihan++;
    }

    function cek() {
        const hapuses = $('[id^="hapus_"]');
        if ($('input[name="pilihan[]"]').length <= 1) {
            [...hapuses].map(section => $(section).prop('disabled', true));
        } else {
            [...hapuses].map(section => $(section).prop('disabled', false));
        }

        const kunciJawaban = document.getElementById('kunciJawaban').value;
        $('#jawaban_' + kunciJawaban + '').prop('disabled', true);
        $('#jawaban_' + kunciJawaban + '').removeClass('btn-outline-warning')
        $('#jawaban_' + kunciJawaban + '').addClass('btn-warning')

        $('#hapus_' + kunciJawaban + '').prop('disabled', true);
    }

    function hapusPilihan(id) {
        const id_pilihan = ($('#id_pilihan_' + id).val()).split("_");

        if (id_pilihan[0] != "new") {
            $("#form").append(`<input type="hidden" name="id_deleted[]" value="` + id_pilihan + `"/>`);
        }
        $("#pilihan_" + id).remove();
        cek();
    }

    function jadikanKunci(id) {
        $('[id^="jawaban_"]').prop('disabled', false);
        $('[id^="jawaban_"]').removeClass('btn-warning')
        $('[id^="jawaban_"]').addClass('btn-outline-warning')
        $('[id^="hapus_"]').prop('disabled', false);
        for (let i = 0; i < $('input[name="pilihan[]"]').length; i++) {
            if ($('#id_pilihan_' + id).val() == id) {
                document.getElementById("kunciJawaban").value = $('#id_pilihan_' + id).val();
                $('#jawaban_' + id + '').prop('disabled', true);

                $('#jawaban_' + id + '').removeClass('btn-outline-warning')
                $('#jawaban_' + id + '').addClass('btn-warning')

                $('#hapus_' + id + '').prop('disabled', true);
            }
        }
    }

    function invalidInput(error) {
        toastr.error(error);
    }

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            invalidInput('{{ $error }}');
        @endforeach
    @endif
</script>
@endpush
