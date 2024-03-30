@extends('layouts/admin/app')

@section('title')
Data Tonas
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Tonas</li>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{-- <button onclick="addForm('{{ route('bius.himada.store') }}')" class="btn btn-outline-success"><i class="fa fa-plus-circle"></i> Tambah</button> --}}
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="" method="post" class="form-member">
                        @csrf
                        <table class="table table-bordered table-striped center-header" id="Table-User">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No. HP</th>
                                    <th>Asal</th>
                                    <th>Referal</th>
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

@includeIf('bius.himada.form')

@endsection

@push('scripts')
<script>
    let tableUser;
    // CSRF Token
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(function() {
        tableUser = $('#Table-User').DataTable({
            processing: true
            , serverside: true
            , responsive: true
            , autoWidth: false
            , ajax: {
                url: '{{ route('admin.tonas.data') }}'
            , }
            , columns: [{
                    data: 'DT_RowIndex'
                    , searchable: false
                    , sortable: false
                }
                , {
                    data: 'name'
                }
                , {
                    data: 'email'
                }
                , {
                    data: 'no_hp'
                }
                , {
                    data: 'asal'
                }
                , {
                    data: 'referal'
                }
            , ]
            , dom: '<"container-fluid"<"row"<"col"B><"col"l><"col"f>>>rtip'
            , buttons: [
                'copy', 'excel', 'pdf'
            ],
            columnDefs: [
                { className: 'text-center', targets: [0, 3] },
            ]
        });
    });

    function storeKelompok(id) {
        const url = '{{ route('admin.bius.store') }}'
        $.post(url, {
            '_token': '{{ csrf_token() }}',
            '_method': 'post',
            'id': id,
            'kelompok': $('#kel-' + id).val(),
        })
        .done((response) => {
            tableUser.ajax.reload();
            toastr.options = {"positionClass": "toast-bottom-right"};
            toastr.success(response);
        })
        .fail((response) => {
            toastr.error('Proses gagal.');
            return;
        })
    }
</script>
@endpush
