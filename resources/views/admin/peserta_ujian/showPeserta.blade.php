@extends('layouts/admin/app')

@section('title')
Data Jawaban
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item"><a href="{{ route('admin.peserta_ujian.index') }}">Ujian</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.peserta_ujian.show', $pembelian->ujian->id) }}">{{ $pembelian->ujian->nama }}</a></li>
<li class="breadcrumb-item active">{{ $pembelian->user->name }}</li>
@endsection

@section('content')
<script type="text/javascript">
    document.body.classList.add('sidebar-collapse');
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="mb-5">
                        <tr>
                            <td style="width: 40%"><h5>Nama Ujian</h5></td>
                            <td><h5>: {{ $pembelian->ujian->nama }}</h5></td>
                        </tr>
                        <tr>
                            <td style="width: 40%"><h5>Nama Peserta</h5></td>
                            <td><h5>: {{ $pembelian->user->name }}</h5></td>
                        </tr>
                        <tr>
                            <td style="width: 40%"><h5>Email Peserta</h5></td>
                            <td><h5>: {{ $pembelian->user->email }}</h5></td>
                        </tr>
                        <tr>
                            @php
                                $waktu_mulai = Carbon\Carbon::parse($pembelian->waktu_mulai_pengerjaan);
                                $waktu_selesai = Carbon\Carbon::parse($pembelian->waktu_selesai_pengerjaan);
                            @endphp

                            <td style="width: 40%"><h5>Waktu Mengerjakan</h5></td>
                            <td><h5>: {{ $waktu_mulai->isoFormat('DD/MM/YYYY hh:mm:ss') }} - {{ $waktu_selesai->isoFormat('DD/MM/YYYY hh:mm:ss') }} <span class="badge badge-success">({{ $waktu_selesai->diff($waktu_mulai)->format('%H:%I:%S'); }})</span></h5></td>
                        </tr>
                        <tr>
                            <td style="width: 40%"><h5>Nilai</h5></td>
                            <td><h5>: <span class="badge badge-success">{{ round($benar / $pembelian->ujian->jumlah_soal * 100, 2) }}</span></h5></td>
                        </tr>
                    </table>
                    <table class="table table-bordered table-striped center-header" id="Table-Soal">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Soal</th>
                                <th style="width: 5%">Status</th>
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
<script>
    let tableSoal;
    $(function() {
        tableSoal = $('#Table-Soal').DataTable({
            processing: true
            , responsive: true
            , autoWidth: false
            , ajax: {
                url: '{{ route('admin.peserta_ujian.show_data_peserta', $pembelian->id) }}'
            , }
            , columns: [{
                    data: 'DT_RowIndex'
                    , searchable: false
                    , sortable: false
                }
                , {
                    data: 'soal'
                }
                , {
                    data: 'status'
                }
            , ]
            , dom: '<"container-fluid"<"row"<"col"B><"col"l><"col"f>>>rtip'
            , buttons: [
                'copy', 'excel', 'pdf'
            ]
            , columnDefs: [
                { className: 'text-center', targets: [0, 2] },
            ]
        });
    });
</script>
@endpush
