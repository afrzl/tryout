@extends('layouts/app')

@section('title')
Data Soal Ujian {{ $ujian->nama }}
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item"><a href="{{ route('admin.ujian.index') }}">Ujian</a></li>
<li class="breadcrumb-item active">Soal {{ $ujian->nama }}</li>
@endsection

@section('content')
<script type="text/javascript">
    document.body.classList.add('sidebar-collapse');
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row">
                    @if(!$ujian->isPublished)
                    <a href="{{ route('admin.ujian.soal.create', $ujian->id) }}" class="btn btn-outline-success"><i class="fa fa-plus-circle"></i> Tambah</a>
                    @endif
                    @if ($ujian->soal->count() >= $ujian->jumlah_soal)
                        @if($ujian->isPublished)
                            <button onclick="cancelPublished('{{ route('admin.ujian.publish', $ujian->id) }}')" type="button" class="btn btn-danger ml-3"><i class="fa fa-sign-out-alt" aria-hidden="true"></i> Batalkan publish</button>
                        @else
                            <button onclick="published('{{ route('admin.ujian.publish', $ujian->id) }}')" type="button" class="btn btn-warning ml-3"><i class="fa fa-sign-out-alt" aria-hidden="true"></i> Publish</button>
                        @endif
                    @endif
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div id="progress" class="progress mb-3">
                        <div class="progress-bar bg-{{$ujian->soal->count() >= $ujian->jumlah_soal ? 'danger' : 'success'}}" role="progressbar" aria-valuenow="{{ $ujian->soal->count() }}" aria-valuemin="0" aria-valuemax="{{ $ujian->jumlah_soal }}" style="width: {{ $ujian->soal->count() / $ujian->jumlah_soal * 100 }}%">
                        {{ $ujian->soal->count() }} dari {{ $ujian->jumlah_soal }} soal
                        </div>
                    </div>
                    <form action="" method="post" class="form-member">
                        @csrf
                        <table class="table table-bordered table-striped center-header" id="Table-Soal">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Soal</th>
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
@endsection

@push('scripts')
<script>
    document.body.classList.add('sidebar-collapse');

    let tableSoal;
    $(function() {
        tableSoal = $('#Table-Soal').DataTable({
            processing: true
            , responsive: true
            , autoWidth: false
            , ajax: {
                url: '{{ route('admin.soal.data', $ujian->id) }}'
            , }
            , columns: [{
                    data: 'DT_RowIndex'
                    , searchable: false
                }
                , {
                    data: 'soal'
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
                { className: 'text-center', targets: [0, 2] },
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
                    tableSoal.ajax.reload();
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
                    tableSoal.ajax.reload();
                    $( "#progress" ).load(window.location.href + " #progress>*" );
                    toastr.options = {
                        "positionClass": "toast-bottom-right",
                        "closeButton" : true,
                        "progressBar" : true
                    };
                    toastr.success('Data berhasil dihapus.');
                })
                .fail((response) => {
                    toastr.error('Tidak dapat menghapus data.');
                    return;
                })
            }
        })
    }

    function published(url) {
        Swal.fire({
            title: 'Apakah kamu yakin akan mempublish ujian?',
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
            }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {
                    '_method': 'get'
                })
                .done((response) => {
                    toastr.options = {
                        "positionClass": "toast-bottom-right",
                        "closeButton" : true,
                        "progressBar" : true
                    };
                    toastr.success('Ujian berhasil dipublish.');
                    $( ".card-header" ).load(window.location.href + " .card-header>*" );
                    tableSoal.ajax.reload();
                })
                .fail((response) => {
                    toastr.error('Tidak dapat mempublish ujian.');
                    return;
                })
            }
        })
    }

    function cancelPublished(url) {
        Swal.fire({
            title: 'Apakah kamu yakin akan membatalkan publish ujian?',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
            }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {
                    '_method': 'get'
                })
                .done((response) => {
                    toastr.options = {
                        "positionClass": "toast-bottom-right",
                        "closeButton" : true,
                        "progressBar" : true
                    };
                    toastr.success('Publish ujian berhasil dibatalkan.');
                    $( ".card-header" ).load(window.location.href + " .card-header>*" );
                    tableSoal.ajax.reload();
                })
                .fail((response) => {
                    toastr.error('error.');
                    return;
                })
            }
        })
    }

    @if(Session::has('message'))
    toastr.options =
    {
        "positionClass": "toast-bottom-right",
        "closeButton" : true,
        "progressBar" : true
    }
        toastr.success("{{ session('message') }}");
    @endif
</script>
@endpush
