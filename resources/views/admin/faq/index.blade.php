@extends('layouts/admin/app')

@section('title')
Data FAQ
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item active">FAQ</li>
@endsection

@push('links')
<!-- Popperjs -->
<link rel="stylesheet" href="{{ asset('adminLTE') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" href="{{ route('admin.faq.create') }}" class="btn btn-outline-success"><i class="fa fa-plus-circle"></i> Tambah</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="" method="post" class="form-member">
                        @csrf
                        <table class="table table-bordered table-striped center-header" id="Table-Pengumuman">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Judul</th>
                                    <th>Isi</th>
                                    <th>Author</th>
                                    <th style="width: 15%"><i class="fa fa-cog"></i></th>
                                </tr>
                            </thead>
                        </table>
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
{{-- Daterange picker --}}
<script src="{{ asset('adminLTE') }}/plugins/moment/moment.min.js" defer></script>

<script src="{{ asset('adminLTE') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js" defer></script>

<script src="{{ asset('adminLTE') }}/plugins/select2/js/select2.full.min.js"></script>

<script>
    @if(Session::has('message'))
        toastr.options =
        {
            "positionClass": "toast-bottom-right",
            "closeButton" : true,
            "progressBar" : true
        }
        toastr.success("{{ session('message') }}");
    @endif

    let tablePengumuman;
    $(function() {
        tablePengumuman = $('#Table-Pengumuman').DataTable({
            processing: true
            , responsive: true
            , autoWidth: false
            , ajax: {
                url: '{{ route('admin.faq.data') }}'
            , }
            , columns: [
                {
                    data: 'DT_RowIndex'
                    , searchable: false
                    , sortable: false
                },
                { data: 'title'},
                { data: 'content'},
                { data: 'author'},
                {
                    data: 'aksi'
                    , searchable: false
                    , sortable: false
                }
            , ]
            , dom: '<"container-fluid"<"row"<"col"B><"col"l><"col"f>>>rtip'
            , buttons: [
                'copy', 'excel', 'pdf'
            ]
            , columnDefs: [
                { className: 'text-center', targets: [0, 4] },
            ]
        });
    });

    function deleteData(url) {
        Swal.fire({
            title: 'Apakah kamu yakin akan menghapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
            }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    tablePengumuman.ajax.reload();
                    toastr.success('Data berhasil dihapus.');
                    toastr.options = {"positionClass": "toast-bottom-right"};
                })
                .fail((response) => {
                    toastr.error('Tidak dapat menghapus data.');
                    return;
                })
            }
        })
    }

    function pinData(url) {
        Swal.fire({
            title: 'Apakah kamu yakin akan menyematkan FAQ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
            }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post'
                })
                .done((response) => {
                    tablePengumuman.ajax.reload();
                    if(response) {
                        toastr.success('FAQ berhasil disematkan.');
                    } else {
                        toastr.success('Sematan FAQ berhasil dilepas.');
                    }
                    toastr.options = {"positionClass": "toast-bottom-right"};
                })
                .fail((response) => {
                    toastr.error('Tidak dapat menyematkan data.');
                    return;
                })
            }
        })
    }
</script>
@endpush
