@extends('layouts.user.app')

@section('title')
{{ $ujian->nama }}
@endsection

@section('content')
<main id="main">
    <div class="container mb-4" style="margin-top: 124px">
        <div class="row" style="justify-content:center">
            <div class="col-lg-12">
                @if(Carbon\Carbon::now() > $ujian->waktu_akhir)
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title"><b>Peringkat Peserta</b></h3>
                        <p class="card-text" style="font-size: 17px">
                            Peringkat Nasional : <span class="badge badge-primary">{{ $rank }} dari {{ $totalRank }}</span> peserta
                            <br />
                            Peringkat Formasi : <span class="badge bg-success">{{ $rankUserFormasi }} dari {{ $totalRankFormasi }}</span> peserta
                        </p>
                    </div>
                </div>
                @else
                <div class="alert alert-primary" role="alert">
                    Pemeringkatan nasional maupun formasi akan tersedia mulai {{ Carbon\Carbon::parse($ujian->waktu_akhir)->isoFormat('D MMMM Y HH:mm:ss') }}
                </div>
                @endif
            </div>
            <div class="col-lg-5 mb-3">
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title"><b>{{ $ujian->nama }}</b></h3>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Nama peserta</h6>
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
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Waktu Pengerjaan</h6>
                                        <span>{{ \Carbon\Carbon::parse($ujian->ujianUser[0]->waktu_mulai)->isoFormat('D MMM Y HH:mm:ss') }} - {{ \Carbon\Carbon::parse($ujian->ujianUser[0]->waktu_akhir)->isoFormat('D MMM Y HH:mm:ss') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Soal Terjawab</h6>
                                        <span>{{ $ujian->ujianUser[0]->jawabanPeserta->where('jawaban_id', '!=', NULL)->count() }} / {{ $ujian->ujianUser[0]->jawabanPeserta->count() }}</span>
                                    </td>
                                </tr>
                                @if($ujian->jenis_ujian == 'skd')
                                <tr>
                                    <td>
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Nilai TWK</h6>
                                        <span>{{ $ujian->ujianUser[0]->nilai_twk }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Nilai TIU</h6>
                                        <span>{{ $ujian->ujianUser[0]->nilai_tiu }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Nilai TKP</h6>
                                        <span>{{ $ujian->ujianUser[0]->nilai_tkp }}</span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Jumlah Nilai</h6>
                                        <span>{{ $ujian->ujianUser[0]->nilai }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">History Jawaban Ujian</h5>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width:15%">No.</th>
                                    <th>Jawaban</th>
                                    <th>Kunci</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ujian->ujianUser[0]->jawabanPeserta as $key => $jawaban)
                                    <tr>
                                        <td>{{ $key+1 }}.</td>
                                        @if($jawaban->jawaban_id == NULL)
                                            <td>-</td>
                                        @else
                                            @foreach ($jawaban->soal->jawaban as $key => $jwb)
                                                @if($jwb->id == $jawaban->jawaban_id)
                                                <td>{{ chr($key+65) }}</td>
                                                @endif
                                            @endforeach
                                        @endif
                                        @foreach ($jawaban->soal->jawaban as $key => $jwb)
                                            @if($jawaban->soal->jenis_soal == 'tkp')
                                                @if($jwb->point == 5)
                                                    <td>{{ chr($key+65) }}</td>
                                                @endif
                                            @else
                                                @if($jwb->id == $jawaban->soal->kunci_jawaban)
                                                    <td>{{ chr($key+65) }}</td>
                                                @endif
                                            @endif
                                        @endforeach
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
