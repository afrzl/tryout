@extends('layouts.user.app')

@section('title')
{{ $pembelian->ujian->nama }}
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Ujian</li>
@endsection

@section('content')
<div class="row" style="justify-content:center">
    <div class="col-lg-6 col-md-8 mx-auto">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>{{ $pembelian->ujian->nama }}</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <tbody>
                            <tr>
                                <td style="text-align: right; width: 50%">
                                    <h6 class="mb-0 mr-6">Waktu Pengerjaan</h6>
                                </td>
                                <td>
                                    <p class="font-weight-bold mb-0">{{ \Carbon\Carbon::parse($pembelian->ujian->waktu_mulai)->isoFormat('D MMMM Y HH:mm:ss') }} - <br>{{ \Carbon\Carbon::parse($pembelian->ujian->waktu_akhir)->isoFormat('D MMMM Y HH:mm:ss') }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    <h6 class="mb-0 mr-6">Lama Ujian</h6>
                                </td>
                                <td>
                                    <p class="font-weight-bold mb-0">{{ $pembelian->ujian->lama_pengerjaan }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    <h6 class="mb-0 mr-6">Jumlah Soal</h6>
                                </td>
                                <td>
                                    <p class="font-weight-bold mb-0">{{ $pembelian->ujian->jumlah_soal }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    <h6 class="mb-0 mr-6">Skor Maksimal</h6>
                                </td>
                                <td>
                                    <p class="font-weight-bold mb-0"><span class="badge badge-sm bg-gradient-success">550</span></p>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align:right;">
                                    <form action="{{ route('ujian.mulai', $pembelian->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn bg-gradient-success mt-4 mb-0">Kerjakan</button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush
