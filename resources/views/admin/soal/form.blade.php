@extends('layouts/admin/app')

@section('title')
Data Soal Ujian {{ $ujian->nama }}
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item"><a href="{{ route('admin.ujian.index') }}">Ujian</a></li>
<li class="breadcrumb-item active"><a href="{{ route('admin.ujian.soal.index', $ujian->id) }}">Soal {{ $ujian->nama }}</a></li>
@endsection


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
                    <form id="form" action="{{ route($action, $id_route) }}" method="post" class="form-horizontal needs-validation" autocomplete="off" novalidate>
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
                                    <div class="form-group row required">
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
                            <div class="form-group required">
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
                                        <input type="number" hidden class="form-control point" value="{{ old('point.'.$i, (count($soal->jawaban) == 0 ? '' : $soal->jawaban[$i]->point)) }}" name="point[{{ $i }}]" id="point[{{ $i }}]" placeholder="Point {{ chr($i + 65) }}">
                                    </div>
                                    @error('point.'.$i)
                                    <div class="invalid-feedback">
                                        <h6>{{ $message }}</h6>
                                    </div>
                                @enderror
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
                            <div class="nontkp">
                                <div class="form-group row required">
                                    <label for="kunci_jawaban" class="col-lg-1 col-md-2 col-form-label">Kunci Jawaban</label>
                                    <div class="col-lg-3 col-md-3">
                                        <select class="form-control @error('kunci_jawaban') is-invalid @enderror" name="kunci_jawaban" id="kunci_jawaban">
                                            <option value="">-- Pilih Kunci Jawaban --</option>
                                            @if($action == 'admin.soal.update')
                                                @foreach($soal->jawaban as $key => $jawaban)
                                                <option value="{{ $jawaban->id }}" @if (old('kunci_jawaban', $soal->kunci_jawaban) == $jawaban->id) ? selected :  @endif>{{ chr($key + 65) }}</option>
                                                @endforeach
                                            @else
                                                @for($i = 0; $i < 5; $i++)
                                                <option value="{{ $i }}" @if (old('kunci_jawaban', $soal->kunci_jawaban)) ? selected :  @endif>{{ chr($i + 65) }}</option>
                                                @endfor
                                            @endif
                                        </select>
                                        @error('kunci_jawaban')
                                            <div class="invalid-feedback">
                                                <h6>{{ $message }}</h6>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row required">
                                    <label for="nilai_benar" class="col-lg-1 col-md-2 col-form-label">Nilai Benar</label>
                                    <div class="col-lg-3 col-md-3">
                                        <input type="number" class="form-control" placeholder="Nilai Benar" name="nilai_benar" value="{{ $soal->poin_benar }}">
                                    </div>
                                    @error('nilai_benar')
                                        <div class="invalid-feedback">
                                            <h6>{{ $message }}</h6>
                                        </div>
                                    @enderror

                                    <label for="nilai_kosong" class="col-lg-1 col-md-2 col-form-label">Nilai Kosong</label>
                                    <div class="col-lg-3 col-md-3">
                                        <input type="number" class="form-control" placeholder="Nilai Kosong" name="nilai_kosong" value="{{ $soal->poin_kosong }}">
                                    </div>
                                    @error('nilai_kosong')
                                        <div class="invalid-feedback">
                                            <h6>{{ $message }}</h6>
                                        </div>
                                    @enderror

                                    <label for="nilai_salah" class="col-lg-1 col-md-2 col-form-label">Nilai Salah</label>
                                    <div class="col-lg-3 col-md-3">
                                        <input type="number" class="form-control" placeholder="Nilai Salah" name="nilai_salah" value="{{ $soal->poin_salah }}">
                                    </div>
                                    @error('nilai_salah')
                                        <div class="invalid-feedback">
                                            <h6>{{ $message }}</h6>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="pembahasan" class="col-sm-3 col-form-label">Pembahasan</label>
                                    <textarea id="pembahasan" class="@error('pembahasan') is-invalid @enderror" autofocus hidden name="pembahasan">{{ old('pembahasan', $soal->pembahasan) }}
                                    </textarea>
                                    @error('pembahasan')
                                        <div class="invalid-feedback">
                                            <h6>{{ $message }}</h6>
                                        </div>
                                    @enderror
                                </div>
                            </div>
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
<script type="text/javascript">
    let countPilihan = 1;
    document.body.classList.add('sidebar-collapse');

    document.addEventListener("DOMContentLoaded", function() {
        if($('#form [name=jenis_ujian]').val() != 'skd' || $('#form [name=jenis_soal]').val() != 'tkp') {
            nonTKPMode();
        } else {
            TKPMode();
        }
    });

    $(function() {
        $('#soal').summernote({
            height: 250,
        });
        $('#pembahasan').summernote({
            height: 150,
        });
        $('.jawaban').summernote({
            height: 100,
        });

        $('#form [name=jenis_soal]').change(function() {
            if ($(this).val() == 'tkp')
                TKPMode();
            else
                nonTKPMode();
        });
    });

    function TKPMode() {
        $('.point').attr('hidden', false);
        $('.nontkp').attr('hidden', true);
    }

    function nonTKPMode() {
        $('.point').attr('hidden', true);
        $('.nontkp').attr('hidden', false);
    }
</script>
@endpush
