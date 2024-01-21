@extends('layouts/admin/app')

@section('title')
Data Ujian
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Ujian</li>
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
                        <table class="table table-bordered table-striped center-header" id="Table-Ujian">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Nama</th>
                                    <th>Jumlah Soal</th>
                                    <th style="width: 10%">Lama Pengerjaan</th>
                                    <th style="width: 30%">Waktu Pengerjaan</th>
                                    <th>Harga</th>
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

@includeIf('ujian.form')
@endsection

@push('scripts')
{{-- Daterange picker --}}
<script src="{{ asset('adminLTE') }}/plugins/moment/moment.min.js" defer></script>

<script src="{{ asset('adminLTE') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js" defer></script>

<script>
    let tableUjian;
    $(function() {
        tableUjian = $('#Table-Ujian').DataTable({
            processing: true
            , responsive: true
            , autoWidth: false
            , ajax: {
                url: '{{ route('admin.ujian.data') }}'
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
                    data: 'jumlah_soal'
                }
                , {
                    data: 'lama_pengerjaan'
                }
                , {
                    data: 'waktu_pengerjaan'
                }
                , {
                    data: 'harga'
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

        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
        .forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
        })

        $('#modal-form form').on('submit', function(e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                .done((response) => {
                    $('#modal-form').modal('hide');
                    tableUjian.ajax.reload();
                    toastr.success('Data berhasil disimpan.');
                    toastr.options = {"positionClass": "toast-bottom-right"};
                })
                .fail((errors) => {
                    // toastr.error('Tidak dapat menyimpan data.');
                    return;
                });
                }
            }
        )
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Ujian');

        $('#modal-form form')[0].classList.remove('was-validated');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();
    }

    function editData(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Ujian');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama]').focus();

        $.get(url)
            .done((response) => {
                var lama_pengerjaan = response.lama_pengerjaan.split(":");
                $('#modal-form [name=nama]').val(response.nama);
                $('#modal-form [name=jenis_ujian]').val(response.jenis_ujian);
                $('#modal-form [name=waktu_mulai]').val(moment(response.waktu_mulai).format('D/MM/YYYY HH:mm'));
                $('#modal-form [name=waktu_akhir]').val(moment(response.waktu_akhir).format('D/MM/YYYY HH:mm'));
                $('#modal-form [name=jam]').val(lama_pengerjaan[0]);
                $('#modal-form [name=menit]').val(lama_pengerjaan[1]);
                $('#modal-form [name=detik]').val(lama_pengerjaan[2]);
                $('#modal-form [name=harga]').val(response.harga);
                $('#modal-form [name=jumlah_soal]').val(response.jumlah_soal);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data.');
                return;
            })
    }

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
                    tableUjian.ajax.reload();
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

    //date time picker
    $(function () {
        $('#Waktu_mulai').datetimepicker({
            icons: { time: 'far fa-clock' },
            format: 'DD/MM/YYYY HH:mm'
        });
        $('#Waktu_akhir').datetimepicker({
            useCurrent: false,
            icons: { time: 'far fa-clock' },
            format: 'DD/MM/YYYY HH:mm'
        });
        $("#Waktu_mulai").on("change.datetimepicker", function (e) {
            $('#Waktu_akhir').datetimepicker('minDate', e.date);
        });
        $("#Waktu_akhir").on("change.datetimepicker", function (e) {
            $('#Waktu_mulai').datetimepicker('maxDate', e.date);
        });
    })
</script>
@endpush
