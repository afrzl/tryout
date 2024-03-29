@extends('layouts.user.app')

@section('title')
    {{ $ujian->nama }}
@endsection

@section('content')
    <main id="main">
        <div class="container mb-4" style="margin-top: 124px">
            <div class="row" style="justify-content:center">
                <div class="col-lg-5 mb-3">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h3 class="card-title"><b>{{ $ujian->nama }}</b></h3>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Nama peserta
                                            </h6>
                                            <span>{{ auth()->user()->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Ujian</h6>
                                            <span>{{ $ujian->nama }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Waktu
                                                Pengerjaan</h6>
                                            <span>{{ \Carbon\Carbon::parse($ujian->ujianUser[0]->waktu_mulai)->isoFormat('D MMM Y HH:mm:ss') }}
                                                -
                                                {{ \Carbon\Carbon::parse($ujian->ujianUser[0]->waktu_akhir)->isoFormat('D MMM Y HH:mm:ss') }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Soal Terjawab
                                            </h6>
                                            <span>{{ $ujian->ujianUser[0]->jawabanPeserta->where('jawaban_id', '!=', null)->count() }}
                                                / {{ $ujian->ujianUser[0]->jawabanPeserta->count() }}</span>
                                        </td>
                                    </tr>
                                    @if ($ujian->tampil_nilai == 1 || ($ujian->tampil_nilai == 2 && \Carbon\Carbon::now() > $ujian->waktu_akhir))
                                        @if ($ujian->jenis_ujian == 'skd')
                                            @php
                                                $twk = $ujian->ujianUser[0]->nilai_twk >= 65;
                                                $tiu = $ujian->ujianUser[0]->nilai_tiu >= 80;
                                                $tkp = $ujian->ujianUser[0]->nilai_tkp >= 166;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Nilai
                                                        TWK</h6>
                                                    <span
                                                        class="badge bg-{{ $twk ? 'success' : 'danger' }}">{{ $ujian->ujianUser[0]->nilai_twk }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Nilai
                                                        TIU</h6>
                                                    <span
                                                        class="badge bg-{{ $tiu ? 'success' : 'danger' }}">{{ $ujian->ujianUser[0]->nilai_tiu }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Nilai
                                                        TKP</h6>
                                                    <span
                                                        class="badge bg-{{ $tkp ? 'success' : 'danger' }}">{{ $ujian->ujianUser[0]->nilai_tkp }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>
                                                <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Jumlah
                                                    Nilai</h6>
                                                <span>{{ $ujian->ujianUser[0]->nilai }}</span>
                                            </td>
                                        </tr>
                                        @if ($ujian->jenis_ujian == 'skd')
                                            <tr>
                                                <td>
                                                    <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Status
                                                        Nilai</h6>
                                                    <span
                                                        class="badge bg-{{ $twk && $tiu && $tkp ? 'success' : 'danger' }}">{{ $twk && $tiu && $tkp ? 'Memenuhi ambang batas' : 'Tidak memenuhi ambang batas' }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($ujian->jenis_ujian == 'mtk')
                                            <tr>
                                                <td>
                                                    <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Status
                                                        Nilai</h6>
                                                    <span
                                                        class="badge bg-{{ $ujian->ujianUser[0]->nilai >= 65 ? 'success' : 'danger' }}">{{ $ujian->ujianUser[0]->nilai >= 65 ? 'Memenuhi ambang batas' : 'Tidak memenuhi ambang batas' }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($ujian->tipe_ujian == 2)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h3 class="card-title mb-3"><b>Grafik Nilai</b></h3>
                            <div class="chart">
                                <canvas id="myChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-lg-7">
                    <div class="col-lg-12">
                        @if (Carbon\Carbon::now() > $ujian->waktu_pengumuman)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h3 class="card-title"><b>Peringkat Peserta</b></h3>
                                    <p class="card-text" style="font-size: 17px">
                                        Peringkat Nasional : <span class="badge badge-primary">{{ $rank }} dari
                                            {{ $totalRank }}</span> peserta
                                        <br />
                                        Peringkat Formasi : <span class="badge bg-success">{{ $rankUserFormasi }} dari
                                            {{ $totalRankFormasi }}</span> peserta
                                    </p>
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="nasional-tab" data-bs-toggle="tab"
                                                data-bs-target="#nasional" type="button" role="tab"
                                                aria-controls="nasional" aria-selected="true">Pemeringkatan
                                                Nasional</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="formasi-tab" data-bs-toggle="tab"
                                                data-bs-target="#formasi" type="button" role="tab"
                                                aria-controls="formasi" aria-selected="false">Pemeringkatan Formasi</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="nasional" role="tabpanel"
                                            aria-labelledby="nasional-tab">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 15%">Peringkat</th>
                                                        <th>Nama Peserta</th>
                                                        <th>Nilai</th>
                                                        @if ($ujian->jenis_ujian == 'skd' || $ujian->jenis_ujian == 'mtk')
                                                            <th>Keterangan</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($ujianUser as $no => $user)
                                                        @if ($no < 15 || $no == $rank - 1)
                                                            <tr>
                                                                <td style="text-align: center">
                                                                    @if ($no == $rank - 1)
                                                                        <b>{{ $no + 1 }}.</b>
                                                                    @else
                                                                        {{ $no + 1 }}.
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($no == $rank - 1)
                                                                        <b>{{ $user->user->name }}</b>
                                                                    @else
                                                                        {{ $user->user->name }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($no == $rank - 1)
                                                                        <b>{{ $user->nilai }}</b>
                                                                    @else
                                                                        {{ $user->nilai }}
                                                                    @endif
                                                                </td>
                                                                @if ($ujian->jenis_ujian == 'skd' || $ujian->jenis_ujian == 'mtk')
                                                                    <td><span
                                                                            class="badge bg-{{ $user->status_kelulusan ? 'success' : 'danger' }}">{{ $user->status_kelulusan ? 'Lulus' : 'Tidak Lulus' }}</span>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="formasi" role="tabpanel"
                                            aria-labelledby="formasi-tab">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 15%">Peringkat</th>
                                                        <th>Nama Peserta</th>
                                                        <th>Nilai</th>
                                                        @if ($ujian->jenis_ujian == 'skd' || $ujian->jenis_ujian == 'mtk')
                                                            <th>Keterangan</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($userFormasi as $no => $user)
                                                        @if ($no < 15 || $no == $rankUserFormasi - 1)
                                                            <tr>
                                                                <td style="text-align: center">
                                                                    @if ($no == $rankUserFormasi - 1)
                                                                        <b>{{ $no + 1 }}.</b>
                                                                    @else
                                                                        {{ $no + 1 }}.
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($no == $rankUserFormasi - 1)
                                                                        <b>{{ $user->user->name }}</b>
                                                                    @else
                                                                        {{ $user->user->name }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($no == $rankUserFormasi - 1)
                                                                        <b>{{ $user->nilai }}</b>
                                                                    @else
                                                                        {{ $user->nilai }}
                                                                    @endif
                                                                </td>
                                                                @if ($ujian->jenis_ujian == 'skd' || $ujian->jenis_ujian == 'mtk')
                                                                    <td><span
                                                                            class="badge bg-{{ $user->status_kelulusan ? 'success' : 'danger' }}">{{ $user->status_kelulusan ? 'Lulus' : 'Tidak Lulus' }}</span>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-primary" role="alert">
                                Pemeringkatan nasional maupun formasi akan tersedia mulai
                                {{ Carbon\Carbon::parse($ujian->waktu_pengumuman)->isoFormat('D MMMM Y HH:mm:ss') }}
                            </div>
                        @endif
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title mb-2"><b>History Jawaban Ujian</b></h3>
                            <table class="table table-striped table-hover mt-3">
                                <thead>
                                    <tr>
                                        <th style="width:15%">No.</th>
                                        <th>Jawaban</th>
                                        @if ($ujian->tampil_kunci == 1 || ($ujian->tampil_kunci == 2 && \Carbon\Carbon::now() > $ujian->waktu_akhir))
                                            <th>Kunci</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ujian->ujianUser[0]->jawabanPeserta as $key => $jawaban)
                                        <tr>
                                            <td>{{ $key + 1 }}.</td>
                                            @if ($jawaban->jawaban_id == null)
                                                <td>-</td>
                                            @else
                                                @foreach ($jawaban->soal->jawaban as $key => $jwb)
                                                    @if ($jwb->id == $jawaban->jawaban_id)
                                                        <td>{{ chr($key + 65) }}</td>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if ($ujian->tampil_kunci == 1 || ($ujian->tampil_kunci == 2 && \Carbon\Carbon::now() > $ujian->waktu_akhir))
                                                @foreach ($jawaban->soal->jawaban as $key => $jwb)
                                                    @if ($jawaban->soal->jenis_soal == 'tkp')
                                                        @if ($jwb->point == 5)
                                                            <td>{{ chr($key + 65) }}</td>
                                                        @endif
                                                    @else
                                                        @if ($jwb->id == $jawaban->soal->kunci_jawaban)
                                                            <td>{{ chr($key + 65) }}</td>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@if ($ujian->tipe_ujian == 2)
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" defer></script>
    <script>
        const riwayat = {{ $dataUjianUser->pluck('nilai') }}
        const tanggal = {!! $dataUjianUser->tanggal !!}
        $(function() {
            console.log(riwayat);
            console.log(tanggal);
            const myChart = new Chart("myChart", {
                type: "line",
                data: {
                    labels: tanggal,
                    datasets: [{
                        backgroundColor:"rgba(0,0,255,1.0)",
                        borderColor: 'rgb(75, 192, 192)',
                        data: riwayat,
                        fill: false,
                    }]
                },
                options: {
                    legend: {display: false}
                }
            });
        })
    </script>
@endpush
@endif
