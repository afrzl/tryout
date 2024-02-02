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
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Waktu Mulai Pengerjaan</h6>
                                        <span>{{ \Carbon\Carbon::parse($ujian->ujianUser[0]->waktu_mulai)->isoFormat('D MMMM Y HH:mm:ss') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Waktu Selesai Pengerjaan</h6>
                                        <span>{{ \Carbon\Carbon::parse($ujian->ujianUser[0]->waktu_akhir)->isoFormat('D MMMM Y HH:mm:ss') }}</span>
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
                                        <td>A</td>
                                        <td>C</td>
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
