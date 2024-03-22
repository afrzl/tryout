@extends('layouts/admin/app')

@section('title')
Data Jawaban
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item"><a href="{{ route('admin.peserta_ujian.index') }}">Ujian</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.peserta_ujian.show', $data->ujian->id) }}">{{ $data->ujian->nama }}</a></li>
<li class="breadcrumb-item active">{{ $data->user->name }}</li>
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
                            <td><h5>: {{ $data->ujian->nama }}</h5></td>
                        </tr>
                        <tr>
                            <td style="width: 40%"><h5>Nama Peserta</h5></td>
                            <td><h5>: {{ $data->user->name }}</h5></td>
                        </tr>
                        <tr>
                            <td style="width: 40%"><h5>Email Peserta</h5></td>
                            <td><h5>: {{ $data->user->email }}</h5></td>
                        </tr>
                        <tr>
                            <td style="width: 40%"><h5>Waktu Mengerjakan</h5></td>
                            <td><h5>: {{ Carbon\Carbon::parse($data->waktu_mulai)->isoFormat('DD/MM/YYYY hh:mm:ss') }} - {{ Carbon\Carbon::parse($data->waktu_akhir)->isoFormat('DD/MM/YYYY hh:mm:ss') }} <span class="badge badge-success">({{ Carbon\Carbon::parse($data->waktu_akhir)->diff(Carbon\Carbon::parse($data->waktu_mulai))->format('%H:%I:%S'); }})</span></h5></td>
                        </tr>
                        <tr>
                            <td style="width: 40%"><h5>Nilai</h5></td>
                            <td><h5>: <span class="badge badge-success">{{ $data->nilai }}</span></h5></td>
                        </tr>
                    </table>
                    <table class="table table-bordered table-striped center-header" id="Table-Soal">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Soal</th>
                                <th style="width: 5%">Point</th>
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
                url: '{{ route('admin.peserta_ujian.show_data_peserta', $data->id) }}'
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
                    data: 'point'
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
                { className: 'text-center', targets: [0, 2, 3] },
            ]
        });
    });
</script>
@endpush
