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
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="chart mb-5">
                            <canvas id="barChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <form action="" method="post" class="form-member">
                            @csrf
                            <table class="table table-bordered table-striped center-header" id="Table-User">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
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
                processing: true,
                serverside: true,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('admin.tonas.data') }}',
                },
                columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                }, {
                    data: 'name'
                }, {
                    data: 'email'
                }, {
                    data: 'asal'
                }, {
                    data: 'referal'
                }, ],
                dom: '<"container-fluid"<"row"<"col"B><"col"l><"col"f>>>rtip',
                buttons: [
                    'copy', 'excel', 'pdf'
                ],
                columnDefs: [{
                    className: 'text-center',
                    targets: [0, 4]
                }, ]
            });

            //-------------
            //- BAR CHART -
            //-------------
            var areaChartData = {
                labels: {!! $data[0] !!},
                datasets: [{
                    label: 'Jumlah Peserta',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: {!! $data[1] !!}
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
