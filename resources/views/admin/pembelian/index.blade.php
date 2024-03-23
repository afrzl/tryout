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
                <div class="card-header">
                    <div class="row gap-y-2">
                        <div class="col-lg-4">
                            <select class="form-control paket_ujian" name="paket_ujian" id="Paket_ujian">
                                <option value="all">-- Pilih Paket Ujian --</option>
                            </select>
                        </div>
                        @role('admin')
                        <button onclick="addForm('{{ route('admin.pembelian.store') }}')" class="ml-3 btn btn-outline-success"><i class="fa fa-plus-circle"></i> Tambah Pembelian</button>
                        @endrole
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table hidden id="selected" class="mb-2" style="width: 100%">
                        <tr>
                            <td style="width: 30%"><h5>Nama Paket Ujian</h5></td>
                            <td><h5 id="paketUjian">: Tonas</h5></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><h5>Total Pembelian Sukses</h5></td>
                            <td><h5 id="totalPembelian">: 100</h5></td>
                        </tr>
                        <tr>
                            <td style="width: 30%"><h5>Total Pembayaran</h5></td>
                            <td><h5 id="totalPembayaran">: 100</h5></td>
                        </tr>
                    </table>
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

@includeIf('admin.pembelian.form')
@includeIf('admin.pembelian.detail')

@endsection

@push('scripts')
{{-- Daterange picker --}}
<script src="{{ asset('adminLTE') }}/plugins/moment/moment.min.js" defer></script>
<script src="{{ asset('adminLTE') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js" defer></script>

<script>
    $('#Paket_ujian').select2({
        theme: 'bootstrap4',
    });
    $('#paket').select2({
        theme: 'bootstrap4',
    });

    function detailForm(url) {
        $.get(url)
            .done(response => {
                $('#modal-detail').modal('show');
                $('#modal-detail .modal-title').html('Transaksi <b>' + response.idTransaksi + '</b>');
                $('#modal-detail .text').text("");
                $('#modal-detail [id=namaPaket]').text(response.paket_ujian.nama);
                $('#modal-detail [id=email]').text(response.user.email);
                $('#modal-detail [id=nama]').text(response.user.name);
                $('#modal-detail [id=noHP]').html('<a target="_blank" href="https://wa.me/62'+ (response.user.users_detail.no_hp).slice(1) +'">'+ response.user.users_detail.no_hp +'</a>')
                $('#modal-detail [id=tanggal]').text(response.tanggalTransaksi);
                $('#modal-detail [id=harga]').text(response.hargaTotal);
                $('#modal-detail [id=voucher]').html(response.voucher_id != null ? '<span class="badge badge-primary">'+ response.voucher.kode +'</span>' : '-');
                $('#modal-detail [id=status]').html('<span class="badge badge-' + (response.status == 'Sukses' ? 'success' : (response.status == 'Belum dibayar' ? 'warning' : 'danger')) + '">' + response.status +'</span>');
                $('#modal-detail [id=metode]').html(response.jenis_pembayaran);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data.');
                return;
            })
    }

    $.ajax({
        type: 'GET',
        url: '{{ route('admin.pembelian.dataPaket') }}',
        dataType: 'json'
        }).then(function (res) {
            res.forEach(item => {
                let newOption = new Option(item.nama, item.id, false, false);
                $('#Paket_ujian').append(newOption);
            });
    });

    $.ajax({
        type: 'GET',
        url: '{{ route('admin.pembelian.dataPaket') }}',
        dataType: 'json'
        }).then(function (res) {
            res.forEach(item => {
                let newOption = new Option(item.nama, item.id, false, false);
                $('#paket').append(newOption).trigger('change');
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

        $('#user').select2({
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('admin.pembelian.getUser') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: '{{ csrf_token() }}',
                        search: params.term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#modal-form form').on('submit', function(e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                .done((response) => {
                    $('#modal-form').modal('hide');
                    tablePembelian.ajax.reload();
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.success(response);
                })
                .fail((response) => {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        toastr.error(errors[key][0]);
                    });
                    return;
                });
                }
            }
        )
    });

    $('#Paket_ujian').change(function(){
        tablePembelian.ajax.reload();
        if ($('#Paket_ujian').val() == 'all') {
            $('#selected').attr('hidden', true);
        } else {
            $.ajax({
            url: `/admin/pembelian/getSummary/` + $('#Paket_ujian').val(),
            type: "get",
            cache: false,
            success: function(response) {
                $('#paketUjian').text(response.paketUjian);
                $('#totalPembayaran').text(response.totalPembayaran);
                $('#totalPembelian').text(response.totalPembelian);
                $('#selected').attr('hidden', false);
            },
            error: function(error) {
                let errors = response.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    toastr.error(errors[key][0]);
                });
                return;
            }
        });
        }
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Akun Himada');

        $('#modal-form form')[0].classList.remove('was-validated');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();
    }
</script>

@endpush
