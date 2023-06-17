@extends('layouts.user.app')

@section('title')
Dashboard
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="container row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-1">Daftar Tryout</h6>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    @if (count($ujians) > 0)
                    @foreach ($ujians as $ujian)
                    <div class="col-xl-4 col-md-6 mb-xl-0 mb-4">
                        <div class="card card-blog card-plain">
                            <div class="card">
                                <div class="card-body p-3">
                                    <form method="post" action="{{ route('pembelian.store') }}">
                                        @csrf
                                        @method('post')
                                        <input type="hidden" name="id_ujian" value="{{ $ujian->id }}">

                                        <a href="javascript:;">
                                            <h5>
                                                {{ $ujian->nama }}
                                            </h5>
                                        </a>
                                        <p class="text-gradient text-dark mb-2 text-sm">{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d F Y H:i:s') }} - <br>{{ \Carbon\Carbon::parse($ujian->waktu_akhir)->format('d F Y H:i:s') }}</p>
                                        <p class="mb-4 text-sm">
                                            <span class="badge badge-sm bg-gradient-success">Rp{{ number_format( $ujian->harga , 0 , ',' , '.' ) }}</span>
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <button type="submit" type="button" class="btn btn-outline-primary btn-sm mb-0">Beli</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <h8><i>Belum ada tryout...</i></h8>
                    @endif
                </div>
            </div>
        </div>
        @auth
        <div class="card mb-4">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-1">History Tryout</h6>
            </div>
            <div class="card-body p-3">
                <div class="chart mb-3">
                    <canvas id="chart-line" class="chart-canvas" height="375" width="1087" style="display: block; box-sizing: border-box; height: 300px; width: 869.6px;"></canvas>
                </div>
                <div class="row">
                    @foreach ($history as $history)
                    <div class="col-xl-4 col-md-6 mb-xl-0 mb-4">
                        <div class="card card-blog card-plain">
                            <div class="card">
                                <div class="card-body p-3">
                                    <a href="javascript:;">
                                        <h5>
                                            {{ $history->ujian->nama }}
                                        </h5>
                                    </a>
                                    <p class="text-gradient text-dark mb-2 text-sm">{{ \Carbon\Carbon::parse($history->ujian->waktu_mulai)->format('d F Y H:i:s') }} - <br>{{ \Carbon\Carbon::parse($history->ujian->waktu_akhir)->format('d F Y H:i:s') }}</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="{{ route('ujian.show', $history->ujian_id) }}" type="submit" type="button" class="btn btn-outline-info btn-sm mb-0">Masuk</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endauth
    </div>
    @auth
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0 text-center">{{ auth()->user()->name }}</h6>
                <p class="mb-0 text-center text-sm">SMA Negeri 1 Comal</p>
            </div>
            <div class="card-body p-3">
                <ul class="list-group">
                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                        <div class="avatar me-3">
                            <img src="../assets/img/kal-visuals-square.jpg" alt="kal" class="border-radius-lg shadow">
                        </div>
                        <div class="d-flex align-items-start flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Sophie B.</h6>
                            <p class="mb-0 text-xs">Hi! I need more information..</p>
                        </div>
                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto" href="javascript:;">Reply</a>
                    </li>
                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                        <div class="avatar me-3">
                            <img src="../assets/img/marie.jpg" alt="kal" class="border-radius-lg shadow">
                        </div>
                        <div class="d-flex align-items-start flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Anne Marie</h6>
                            <p class="mb-0 text-xs">Awesome work, can you..</p>
                        </div>
                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto" href="javascript:;">Reply</a>
                    </li>
                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                        <div class="avatar me-3">
                            <img src="../assets/img/ivana-square.jpg" alt="kal" class="border-radius-lg shadow">
                        </div>
                        <div class="d-flex align-items-start flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Ivanna</h6>
                            <p class="mb-0 text-xs">About files I can..</p>
                        </div>
                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto" href="javascript:;">Reply</a>
                    </li>
                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                        <div class="avatar me-3">
                            <img src="../assets/img/team-4.jpg" alt="kal" class="border-radius-lg shadow">
                        </div>
                        <div class="d-flex align-items-start flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Peterson</h6>
                            <p class="mb-0 text-xs">Have a great afternoon..</p>
                        </div>
                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto" href="javascript:;">Reply</a>
                    </li>
                    <li class="list-group-item border-0 d-flex align-items-center px-0">
                        <div class="avatar me-3">
                            <img src="../assets/img/team-3.jpg" alt="kal" class="border-radius-lg shadow">
                        </div>
                        <div class="d-flex align-items-start flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Nick Daniel</h6>
                            <p class="mb-0 text-xs">Hi! I need more information..</p>
                        </div>
                        <a class="btn btn-link pe-3 ps-0 mb-0 ms-auto" href="javascript:;">Reply</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @endauth
</div>
@endsection

@push('scripts')
<script src="{{ asset('softUI') }}/assets/js/plugins/chartjs.min.js"></script>
<script>
    var ctx2 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    new Chart(ctx2, {
        type: "line"
        , data: {
            labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
            , datasets: [{
                    label: "Mobile apps"
                    , tension: 0.4
                    , borderWidth: 0
                    , pointRadius: 0
                    , borderColor: "#cb0c9f"
                    , borderWidth: 3
                    , backgroundColor: gradientStroke1
                    , fill: true
                    , data: [50, 40, 300, 220, 500, 250, 400, 230, 500]
                    , maxBarThickness: 6

                }
                , {
                    label: "Websites"
                    , tension: 0.4
                    , borderWidth: 0
                    , pointRadius: 0
                    , borderColor: "#3A416F"
                    , borderWidth: 3
                    , backgroundColor: gradientStroke2
                    , fill: true
                    , data: [30, 90, 40, 140, 290, 290, 340, 230, 400]
                    , maxBarThickness: 6
                }
            , ]
        , }
        , options: {
            responsive: true
            , maintainAspectRatio: false
            , plugins: {
                legend: {
                    display: false
                , }
            }
            , interaction: {
                intersect: false
                , mode: 'index'
            , }
            , scales: {
                y: {
                    grid: {
                        drawBorder: false
                        , display: true
                        , drawOnChartArea: true
                        , drawTicks: false
                        , borderDash: [5, 5]
                    }
                    , ticks: {
                        display: true
                        , padding: 10
                        , color: '#b2b9bf'
                        , font: {
                            size: 11
                            , family: "Open Sans"
                            , style: 'normal'
                            , lineHeight: 2
                        }
                    , }
                }
                , x: {
                    grid: {
                        drawBorder: false
                        , display: false
                        , drawOnChartArea: false
                        , drawTicks: false
                        , borderDash: [5, 5]
                    }
                    , ticks: {
                        display: true
                        , color: '#b2b9bf'
                        , padding: 20
                        , font: {
                            size: 11
                            , family: "Open Sans"
                            , style: 'normal'
                            , lineHeight: 2
                        }
                    , }
                }
            , }
        , }
    , });

</script>
@endpush
