@extends('layouts/admin/app')

@section('title')
Data Peserta Ujian
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item active"><a href="{{ route('admin.peserta_ujian.index') }}">Peserta Ujian</a></li>
<li class="breadcrumb-item active">{{ $ujian->nama }}</li>
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
                <!-- /.card-header -->
                <div class="card-body">
                    <button onclick="refresh('{{ route('admin.peserta_ujian.refresh', $ujian->id) }}')" type="button" class="btn btn-outline-success btn-sm mb-3 float-right">Refresh</button>
                    <table class="mb-5">
                        <tr>
                            <td style="width: 40%"><h5>Nama Ujian</h5></td>
                            <td><h5>: {{ $ujian->nama }}</h5></td>
                        </tr>
                        <tr>
                            <td style="width: 40%"><h5>Jumlah Soal</h5></td>
                            <td><h5>: {{ $ujian->jumlah_soal }}</h5></td>
                        </tr>
                        <tr>
                            <td style="width: 40%"><h5>Waktu Ujian</h5></td>
                            <td><h5>: {{ Carbon\Carbon::parse($ujian->waktu_mulai)->isoFormat('D MMMM Y HH:mm:ss') }} - {{ Carbon\Carbon::parse($ujian->waktu_akhir)->isoFormat('D MMMM Y HH:mm:ss');}}</h5></td>
                        </tr>
                        <tr>
                            <td style="width: 40%"><h5>Lama Pengerjaan</h5></td>
                            <td><h5>: {{ $ujian->lama_pengerjaan }} Menit</h5></td>
                        </tr>
                    </table>
                    <table class="table table-bordered table-striped center-header" id="Table-Show-Peserta-Ujian">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status Pengerjaan</th>
                                <th>Waktu Pengerjaan</th>
                                <th>Nilai</th>
                                <th style="width: 5%"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                    </table>
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

<script>
    let tableShow;
    $(function() {
        tableShow = $('#Table-Show-Peserta-Ujian').DataTable({
            processing: true
            , responsive: true
            , autoWidth: false
            , ajax: {
                url: '{{ route('admin.peserta_ujian.show_data', $ujian->id) }}'
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
                    data: 'email'
                }
                , {
                    data: 'status_pengerjaan'
                }
                , {
                    data: 'waktu_pengerjaan'
                }
                , {
                    data: 'nilai'
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
                { className: 'text-center', targets: [0, 3, 4, 5, 6] },
            ]
            , order: [[3, 'desc']]
        });
    });

    function refresh(url) {
        $.ajax({
            url: url,
            success: function(satwork) {
                tableShow.ajax.reload();
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                    "closeButton": true,
                    "progressBar": true
                };
                toastr.success('Data berhasil diperbarui.');
            }
        });
    }
</script>

@endpush
