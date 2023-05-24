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
                        @else
                            @method('post')
                        @endif
                        <div id="form">
                            <div class="form-group">
                                <input type="hidden" name="jenis_ujian" value="{{ $ujian->jenis_ujian }}">
                                @if ($ujian->jenis_ujian == 'skd')
                                    <div class="form-group row">
                                        <label for="jenis_soal" class="col-lg-1 col-md-2 col-form-label">Jenis Soal</label>
                                        <div class="col-lg-2 col-md-3">
                                            <select class="form-control @error('jenis_soal') is-invalid @enderror" name="jenis_soal" id="jenis_soal">
                                                <option value="">-- Pilih Jenis Soal --</option>
                                                <option value="twk" @if (old('jenis_soal', $soal->jenis_soal)) ? selected :  @endif>TWK</option>
                                                <option value="tiu" @if (old('jenis_soal', $soal->jenis_soal) == 'tiu') ? selected :  @endif>TIU</option>
                                                <option value="tkp" @if (old('jenis_soal', $soal->jenis_soal) == 'tkp') ? selected :  @endif>TKP</option>
                                            </select>
                                        </div>
                                        @error('jenis_soal')
                                            <div class="invalid-feedback">
                                                <h6>{{ $message }}</h6>
                                            </div>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="soal" class="col-sm-3 col-form-label">Soal</label>
                                <textarea id="soal" class="@error('soal') is-invalid @enderror" autofocus hidden name="soal">{{ old('soal', $soal->soal) }}
                                </textarea>
                                @error('soal')
                                    <div class="invalid-feedback">
                                        <h6>{{ $message }}</h6>
                                    </div>
                                @enderror
                            </div>

                            @for($i = 0; $i < 5; $i++)
                                <div class="form-group row">
                                    <label for="point[{{ $i }}]" class="col-lg-1 col-md-2 col-form-label">Jawaban {{ chr($i + 65) }}</label>
                                    <div class="col-lg-1 col-md-2 pull-right">
                                        <input type="number" class="form-control" value="{{ old('point.'.$i, (count($soal->jawaban) == 0 ? '' : $soal->jawaban[$i]->point)) }}" name="point[{{ $i }}]" id="point[{{ $i }}]" placeholder="Point {{ chr($i + 65) }}">
                                    </div>
                                </div>
                                @if($action == 'admin.soal.update')
                                <input type="hidden" name="id_jawaban[{{ $i }}]" value="{{ $soal->jawaban[$i]->id }}">
                                @endif
                                <textarea class="jawaban @error('jawaban.'.$i) is-invalid @enderror" id="jawaban[{{ $i }}]" hidden name="jawaban[{{ $i }}]">{{ old('jawaban.'.$i, (count($soal->jawaban) == 0 ? '' : $soal->jawaban[$i]->jawaban)) }}
                                </textarea>
                                @error('jawaban.'.$i)
                                    <div class="invalid-feedback">
                                        <h6>{{ $message }}</h6>
                                    </div>
                                @enderror
                            @endfor
                        </div>
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
        $('.jawaban').summernote({
            height: 100,
        });
    });
</script>
@endpush
