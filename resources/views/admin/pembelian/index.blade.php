@extends('layouts/admin/app')

@section('title')
Data Pembelian Paket
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Pembelian Paket</li>
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
                <div class="card-header row">
                    <div class="col-lg-4">
                        <select class="form-control paket_ujian" name="paket_ujian" id="Paket_ujian">
                            <option value="all">-- Pilih Paket Ujian --</option>
                        </select>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="" method="post" class="form-member">
                        @csrf
                        <table class="table table-bordered table-striped center-header" id="Table-Pembelian">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Email Pembeli</th>
                                    <th>Nama Pembeli</th>
                                    <th>Tanggal</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Voucher</th>
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
@endsection

@push('scripts')
{{-- Daterange picker --}}
<script src="{{ asset('adminLTE') }}/plugins/moment/moment.min.js" defer></script>
<script src="{{ asset('adminLTE') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js" defer></script>

<script>
    $('#Paket_ujian').select2({
        theme: 'bootstrap4',
    });

    $.ajax({
        type: 'GET',
        url: '{{ route('admin.pembelian.dataPaket') }}',
        dataType: 'json'
        }).then(function (res) {
            console.log(res);
            res.forEach(item => {
                let newOption = new Option(item.nama, item.id, false, false);
                $('#Paket_ujian').append(newOption).trigger('change');
            });
    });

    let tablePembelian;
    $(function() {
        tablePembelian = $('#Table-Pembelian').DataTable({
            processing: true
            , responsive: true
            , autoWidth: false
            , ajax: {
                url: '{{ route('admin.pembelian.data') }}',
                data: function (d) {
                    d.paket_ujian = $('#Paket_ujian').val(),
                    d.search = $('input[type="search"]').val()
                }
            , }
            , columns: [{
                    data: 'DT_RowIndex'
                    , searchable: false
                    , sortable: false
                }
                , {
                    data: 'email'
                }
                , {
                    data: 'nama'
                }
                , {
                    data: 'created_at'
                }
                , {
                    data: 'jenis_pembayaran'
                }
                , {
                    data: 'voucher'
                }
                , {
                    data: 'harga'
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
                { className: 'text-center', targets: [0, 3, 4, 5, 6, 7] },
            ]
        });
    });

    $('#Paket_ujian').change(function(){
        tablePembelian.ajax.reload();
    });
</script>

@endpush
