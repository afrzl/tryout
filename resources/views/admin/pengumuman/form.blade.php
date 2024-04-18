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
                    <form id="form" action="{{ route($action) }}" method="post" class="form-horizontal needs-validation" autocomplete="off" novalidate>
                        @csrf
                        @if($action == 'admin.pengumuman.update')
                        @method('put')
                        @else
                        @method('post')
                        @endif
                        <div id="form">
                            <div class="form-group required">
                                <label for="title" class="col-sm-3 col-form-label">Judul</label>
                                <input type="text" class="form-control" value="{{ old('title') }}" name="title" id="title" placeholder="Judul" autofocus>
                                @error('title')
                                <div class="invalid-feedback">
                                    <h6>{{ $message }}</h6>
                                </div>
                                @enderror
                            </div>
                            <div class="form-group required">
                                <label for="content" class="col-sm-3 col-form-label">Isi</label>
                                <textarea id="content" class="@error('content') is-invalid @enderror form-control" autofocus name="content"></textarea>
                                @error('content')
                                <div class="invalid-feedback">
                                    <h6>{{ $message }}</h6>
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="file">File Lampiran</label>
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
