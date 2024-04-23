@extends('layouts/admin/app')

@section('title')
Data Peserta Tonas
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Peserta Tonas</li>
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
                <div class="card-body">
                    <table id="selected" class="mb-2" style="width: 100%">
                        <tr>
                            <td style="width: 30%">
                                <h5>Total Pembelian Sukses</h5>
                            </td>
                            <td>
                                <h5 id="totalPembelian">: {{ $pembelian }}</h5>
                            </td>
                        </tr>
                    </table>
                    <div class="chart mb-5">
                        <canvas id="barChart" style="min-height: 250px; height: 300px; max-height: 500px; max-width: 100%;"></canvas>
                    </div>
                    <form action="" method="post" class="form-member">
                        @csrf
                        <table class="table table-bordered table-striped center-header" id="Table-Pembelian">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Email Pembeli</th>
                                    <th>Nama Pembeli</th>
                                    <th>Tanggal</th>
                                    <th>Alamat</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
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

@includeIf('admin.pembelian.detail')
@endsection

@push('scripts')
{{-- Daterange picker --}}
<script src="{{ asset('adminLTE') }}/plugins/moment/moment.min.js" defer></script>
<script src="{{ asset('adminLTE') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js" defer>
</script>

<script>
    function detailForm(url) {
        $.get(url)
            .done(response => {
                $('#modal-detail').modal('show');
                $('#modal-detail .modal-title').html('Transaksi <b>' + response.idTransaksi + '</b>');
                $('#modal-detail .text').text("");
                $('#modal-detail [id=namaPaket]').text(response.paket_ujian.nama);
                $('#modal-detail [id=email]').text(response.user.email);
                $('#modal-detail [id=nama]').text(response.user.name);
                $('#modal-detail [id=noHP]').html('<a target="_blank" href="https://wa.me/62' + (response.user
                    .users_detail.no_hp).slice(1) + '">' + response.user.users_detail.no_hp + '</a>')
                $('#modal-detail [id=tanggal]').text(response.tanggalTransaksi);
                $('#modal-detail [id=harga]').text(response.hargaTotal);
                $('#modal-detail [id=voucher]').html(response.voucher_id != null ?
                    '<span class="badge badge-primary">' + response.voucher.kode + '</span>' : '-');
                $('#modal-detail [id=status]').html('<span class="badge badge-' + (response.status == 'Sukses' ?
                        'success' : (response.status == 'Belum dibayar' ? 'warning' : 'danger')) + '">' +
                    response.status + '</span>');
                $('#modal-detail [id=metode]').html(response.jenis_pembayaran);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data.');
                return;
            })
    }

    let tablePembelian;
    $(function() {
        tablePembelian = $('#Table-Pembelian').DataTable({
            processing: true
            , responsive: true
            , autoWidth: false
            , ajax: {
                url: '{{ route('himada.peserta.data') }}'
                , data: function(d) {
                    d.search = $('input[type="search"]').val()
                }
            , }
            , columns: [{
                data: 'DT_RowIndex'
                , searchable: false
                , sortable: false
            }, {
                data: 'email'
            }, {
                data: 'nama'
            }, {
                data: 'created_at'
            }, {
                data: 'alamat'
            }, {
                data: 'harga'
            }, {
                data: 'status'
            }, ]
            , dom: '<"container-fluid"<"row"<"col"B><"col"l><"col"f>>>rtip'
            , buttons: [
                'copy', 'excel', 'pdf'
            ]
            , columnDefs: [{
                className: 'text-center'
                , targets: [0, 3, 5, 6]
            }, ]
        });

        //-------------
        //- BAR CHART -
        //-------------
        var areaChartData = {
            labels: {!! $kabupaten !!},
            datasets: [{
                label: 'Jumlah Peserta',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: {!! $pesertaKab !!}
            }]
        }

        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        barChartData.datasets[0] = areaChartData.datasets[0]

        var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false,
        }

        new Chart(barChartCanvas, {
            type: 'horizontalBar',
            data: barChartData,
            options: barChartOptions
        })
    });

</script>
@endpush
