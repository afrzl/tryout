@extends('layouts/app')

@section('title')
Data Peserta Ujian
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Peserta Ujian</li>
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
                    <button onclick="addForm('{{ route('admin.ujian.store') }}')" class="btn btn-outline-success"><i class="fa fa-plus-circle"></i> Tambah</button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="" method="post" class="form-member">
                        @csrf
                        <table class="table table-bordered table-striped center-header" id="Table-Peserta-Ujian">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Nama Ujian</th>
                                    <th>Waktu Pelaksanaan</th>
                                    <th>Jumlah Peserta</th>
                                    <th>Dikerjakan</th>
                                    <th>Status</th>
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

@includeIf('peserta_ujian.form')
@endsection

@push('scripts')
{{-- Daterange picker --}}
<script src="{{ asset('adminLTE') }}/plugins/moment/moment.min.js" defer></script>
<script src="{{ asset('adminLTE') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js" defer></script>

<script>
    let tablePesertaUjian;
    $(function() {
        tablePesertaUjian = $('#Table-Peserta-Ujian').DataTable({
            processing: true
            , responsive: true
            , autoWidth: false
            , ajax: {
                url: '{{ route('admin.peserta_ujian.data') }}'
            , }
            , columns: [{
                    data: 'DT_RowIndex'
                    , searchable: false
                    , sortable: false
                }
                , {
                    data: 'nama'
                }
                , {
                    data: 'waktu_pelaksanaan'
                }
                , {
                    data: 'jumlah_peserta'
                }
                , {
                    data: 'peserta_mengerjakan'
                }
                , {
                    data: 'status'
                }
                , {
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
                { className: 'text-center', targets: [0, 2, 3, 4, 5, 6] },
            ]
        });
    });
</script>

@endpush
