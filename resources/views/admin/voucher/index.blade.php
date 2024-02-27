@extends('layouts/admin/app')

@section('title')
Data Voucher
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Voucher</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button onclick="addForm('{{ route('admin.voucher.store') }}')" class="btn btn-outline-success"><i class="fa fa-plus-circle"></i> Tambah</button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="" method="post" class="form-member">
                        @csrf
                        <table class="table table-bordered table-striped center-header" id="Table-User">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Kode</th>
                                    <th>Paket Ujian</th>
                                    <th>Diskon</th>
                                    <th>Sisa Kuota</th>
                                    <th style="width: 10%"><i class="fa fa-cog"></i></th>
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

@includeIf('admin.voucher.form')
@includeIf('admin.voucher.addKuota')

@endsection

@push('scripts')
<script>
    let tableUser;
    // CSRF Token
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(function() {
        $('#Paket').select2()

        tableUser = $('#Table-User').DataTable({
            processing: true
            , serverside: true
            , responsive: true
            , autoWidth: false
            , ajax: {
                url: '{{ route('admin.voucher.data') }}'
            , }
            , columns: [{
                    data: 'DT_RowIndex'
                    , searchable: false
                    , sortable: false
                }
                , {
                    data: 'kode'
                }
                , {
                    data: 'paket-ujian'
                }
                , {
                    data: 'diskon'
                }
                , {
                    data: 'kuota'
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
            ],
            columnDefs: [
                { className: 'text-center', targets: [0, 1, 2, 3, 4] },
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
                    tableUser.ajax.reload();
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
        );

        $('#modal-addKuota form').on('submit', function(e) {
            if (! e.preventDefault()) {
                $.post($('#modal-addKuota form').attr('action'), $('#modal-addKuota form').serialize())
                .done((response) => {
                    $('#modal-addKuota').modal('hide');
                    tableUser.ajax.reload();
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.success(response);
                })
                .fail((errors) => {
                    toastr.error('Tidak dapat menyimpan data.');
                    return;
                });
                }
            }
        )
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Voucher');

        $('#modal-form form')[0].classList.remove('was-validated');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();
    }

    function addKuota(url) {
        $('#modal-addKuota').modal('show');
        $('#modal-addKuota .modal-title').text('Tambah Kuota Voucher');

        $('#modal-addKuota form')[0].reset();
        $('#modal-addKuota form').attr('action', url);
        $('#modal-addKuota [name=_method]').val('put');
        $('#modal-addKuota [name=name]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-addKuota [name=kode]').val(response.kode);
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
                    tableUser.ajax.reload();
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.success('Data berhasil dihapus.');
                })
                .fail((response) => {
                    toastr.error('Tidak dapat menghapus data.');
                    return;
                })
            }
        })
    }

    function generatePass(name) {
        // change 12 to the length you want the hash
        var pass = '';
        var str='ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        +  'abcdefghijklmnopqrstuvwxyz0123456789@#$';

        for (let i = 1; i <= 8; i++) {
            var char = Math.floor(Math.random()* str.length + 1);
            pass += str.charAt(char)
        }
        if (name === 'reset') {
            $('#PasswordReset').val(pass);
        } else if (name === 'new') {
            $('#Password').val(pass);
        }
    }

    function makeAdmin(url) {
        let action = url.split('/')[6];
        let title = (action === 'make' ? 'Apakah anda yakin akan mengubah user menjadi admin?' : 'Apakah anda yakin akan merevoke hak akses admin?');
        let success = action === 'make' ? 'User berhasil menjadi admin' : 'Hak akses admin user direvoke';
        Swal.fire({
            title: title,
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
                    tableUser.ajax.reload();
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.success(success);
                })
                .fail((response) => {
                    toastr.error('Proses gagal.');
                    return;
                })
            }
        })
    }

    function resetPassword(url) {
        $('#modal-reset').modal('show');
        $('#modal-reset .modal-title').text('Reset Password');

        $('#modal-reset form')[0].classList.remove('was-validated');
        $('#modal-reset form')[0].reset();
        $('#modal-reset form').attr('action', url);
        $('#modal-reset [name=_method]').val('post');
        $('#modal-reset [name=name]').focus();
    }

</script>
@endpush
