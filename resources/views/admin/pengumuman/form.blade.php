@extends('layouts/admin/app')

@section('title')
Data Pengumuman
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item"><a href="{{ route('admin.pengumuman.index') }}">Pengumuman</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="form" action="{{ $action }}" method="post" class="form-horizontal needs-validation" autocomplete="off" enctype="multipart/form-data" novalidate>
                        @csrf
                        @if($action == route('admin.pengumuman.store'))
                        @method('post')
                        @else
                        @method('put')
                        @endif
                        <div class="form-group required">
                            <label for="title" class="col-sm-3 col-form-label">Judul</label>
                            <input type="text" class="form-control" value="{{ old('title', $pengumuman->title) }}" name="title" id="title" placeholder="Judul" required>
                            @error('title')
                            <div class="invalid-feedback">
                                <h6>{{ $message }}</h6>
                            </div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label for="content" class="col-sm-3 col-form-label">Isi</label>
                            <textarea id="content" class="@error('content') is-invalid @enderror form-control" name="content">{{ old('content', $pengumuman->content) }}</textarea>
                            @error('content')
                            <div class="invalid-feedback">
                                <h6>{{ $message }}</h6>
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="file">File Lampiran {{ $action != route('admin.pengumuman.store') ? '(tambahkan jika dokumen akan diganti)' : '' }}</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="form-control" value="{{ old('file') }}" name="file" id="file">
                                    <label class="custom-file-label" for="file">Pilih file</label>
                                </div>
                            </div>
                            @error('file')
                            <div class="invalid-feedback">
                                <h6>{{ $message }}</h6>
                            </div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label for="tujuan" class="col-sm-3 col-form-label">Tujuan</label>
                            <select id="tujuan" class="form-control select2" name="tujuan">
                                <option value="" selected="selected">Semua</option>
                                @foreach ($pakets as $paket)
                                    <option value="{{ $paket->id }}" {{ old('file', $pengumuman->paket_id) == $paket->id ? 'selected' : '' }}>{{ $paket->nama }}</option>
                                @endforeach
                            </select>
                            @error('tujuan')
                            <div class="invalid-feedback">
                                <h6>{{ $message }}</h6>
                            </div>
                            @enderror
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
<script src="{{ asset('adminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(function () {
      bsCustomFileInput.init();
    });
</script>
@endpush
