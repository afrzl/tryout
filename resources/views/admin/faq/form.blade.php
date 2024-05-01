@extends('layouts/admin/app')

@section('title')
Data FAQ
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item"><a href="{{ route('admin.faq.index') }}">FAQ</a></li>
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
                        @if($action == route('admin.faq.store'))
                        @method('post')
                        @else
                        @method('put')
                        @endif
                        <div class="form-group required">
                            <label for="title" class="col-sm-3 col-form-label">Judul</label>
                            <input type="text" class="form-control" value="{{ old('title', $faq->title) }}" name="title" id="title" placeholder="Judul" required>
                            @error('title')
                            <div class="invalid-feedback">
                                <h6>{{ $message }}</h6>
                            </div>
                            @enderror
                        </div>
                        <div class="form-group required">
                            <label for="content" class="col-sm-3 col-form-label">Isi</label>
                            <textarea id="content" class="@error('content') is-invalid @enderror" autofocus hidden name="content">{{ old('content', $faq->content) }}
                            </textarea>
                            @error('content')
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

      $('#content').summernote({
            height: 250,
        });
    });
</script>
@endpush
