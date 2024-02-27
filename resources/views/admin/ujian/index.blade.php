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
                                    <th>Jenis Ujian</th>
                                    <th>Jumlah Soal</th>
                                    <th style="width: 10%">Lama Pengerjaan (Menit)</th>
                                    <th style="width: 30%">Waktu Pengerjaan</th>
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

@includeIf('admin.ujian.form')
@includeIf('admin.ujian.detail')
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
                    data: 'jenis_ujian'
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

        $('#modal-form [name=jenis_ujian]').change(function() {
            let tipe = $(this).val();
            if (tipe == 'skd') {
                $('#modal-form [name=lama_pengerjaan]').val('100').attr('readonly', true);
                $('#modal-form [name=jumlah_soal]').val('110').attr('readonly', true);
            } else {
                $('#modal-form [name=lama_pengerjaan]').val('').attr('readonly', false);
                $('#modal-form [name=jumlah_soal]').val('').attr('readonly', false);
            }
        })
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Ujian');

        $('#modal-form form')[0].classList.remove('was-validated');
        $('#modal-form form')[0].reset();
        $('#modal-form [name=deskripsi]').summernote('code', '');
        $('#modal-form [name=peraturan]').summernote('code', '');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();
    }

    function detailForm(url) {
        $.get(url)
            .done((response) => {
                $('#modal-detail').modal('show');
                $('#modal-detail .modal-title').text(response.nama);
                $('#modal-detail [id=idUjian]').text(response.id);
                $('#modal-detail [id=namaUjian]').text(response.nama);
                $('#modal-detail [id=jenisUjian]').text(response.jenis_ujian);
                $('#modal-detail [id=durasi]').text(response.lama_pengerjaan + ' Menit');
                $('#modal-detail [id=deskripsi]').html(response.deskripsi);
                $('#modal-detail [id=peraturan]').html(response.peraturan);
                $('#modal-detail [id=waktuMulai]').text(response.waktu_mulai);
                $('#modal-detail [id=waktuAkhir]').text(response.waktu_akhir);
                $('#modal-detail [id=waktuPengumuman]').text(response.waktu_pengumuman);
                $('#modal-detail [id=published]').text(response.isPublished == 0 ? 'Belum Publish' : 'Published');
                $('#modal-detail [id=jumlahSoal]').text(response.jumlah_soal);
                $('#modal-detail [id=jumlahSoalTerisi]').text(response.soal.length);
                $('#modal-detail [id=tipeUjian]').text(response.tipe_ujian == 1 ? 'Sekali Mengerjakan' : 'Periodik');
                $('#modal-detail [id=tampilkanKunci]').text(response.tampil_kunci == 0 ? 'Tidak' : (response.tampil_kunci == 1 ? 'Ya' : 'Ya, setelah ditutup'));
                $('#modal-detail [id=tampilkanNilai]').text(response.tampil_nilai == 0 ? 'Tidak' : (response.tampil_kunci == 1 ? 'Ya' : 'Ya, setelah ditutup'));
                $('#modal-detail [id=tampilPoin]').text(response.tampil_poin == 0 ? 'Tidak' : 'Tampilkan');
                $('#modal-detail [id=acakSoal]').text(response.random == 0 ? 'Tidak' : 'Acak');
                $('#modal-detail [id=acakPilihan]').text(response.random_pilihan == 0 ? 'Tidak' : 'Acak');
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data.');
                return;
            })
    }

    function editData(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Ujian');

        $('#modal-form form')[0].reset();
        $('#modal-form [name=deskripsi]').summernote('code', '');
        $('#modal-form [name=peraturan]').summernote('code', '');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama]').val(response.nama);
                $('#modal-form [name=jenis_ujian]').val(response.jenis_ujian);
                $('#modal-form [name=deskripsi]').summernote('code', response.deskripsi);
                $('#modal-form [name=peraturan]').summernote('code', response.peraturan);
                $('#modal-form [name=waktu_mulai]').val(moment(response.waktu_mulai).format('D/MM/YYYY HH:mm'));
                $('#modal-form [name=waktu_akhir]').val(moment(response.waktu_akhir).format('D/MM/YYYY HH:mm'));
                $('#modal-form [name=waktu_pengumuman]').val(moment(response.waktu_pengumuman).format('D/MM/YYYY HH:mm'));
                $('#modal-form [name=lama_pengerjaan]').val(parseInt(response.lama_pengerjaan));
                $('#modal-form [name=jumlah_soal]').val(response.jumlah_soal);
                $('#modal-form [name=tipe_ujian]').val(response.tipe_ujian);
                $('#modal-form [name=tampil_kunci]').val(response.tampil_kunci);
                $('#modal-form [name=tampil_nilai]').val(response.tampil_nilai);
                $('#modal-form [name=tampil_poin]').val(response.tampil_poin);
                $('#modal-form [name=random]').val(response.random);
                $('#modal-form [name=random_pilihan]').val(response.random_pilihan);
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
            useCurrent: false,
            icons: { time: 'far fa-clock' },
            format: 'DD/MM/YYYY HH:mm'
        });
        $('#Waktu_akhir').datetimepicker({
            useCurrent: false,
            icons: { time: 'far fa-clock' },
            format: 'DD/MM/YYYY HH:mm'
        });
        $('#Waktu_pengumuman').datetimepicker({
            useCurrent: false,
            icons: { time: 'far fa-clock' },
            format: 'DD/MM/YYYY HH:mm'
        });
        $("#Waktu_mulai").on("change.datetimepicker", function (e) {
            $('#Waktu_akhir').datetimepicker('minDate', e.date);
        });
        $("#Waktu_akhir").on("change.datetimepicker", function (e) {
            $('#Waktu_mulai').datetimepicker('maxDate', e.date);
            $('#Waktu_pengumuman').datetimepicker('minDate', e.date);
        });

        $('#deskripsi').summernote({
            height: 150,
        });
        $('#peraturan').summernote({
            height: 150,
        });
    })
</script>
@endpush
