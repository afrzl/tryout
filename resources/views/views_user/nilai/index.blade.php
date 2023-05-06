@extends('layouts.user.app')

@section('title')
{{ $pembelian->ujian->nama }}
@endsection

@section('content')
<div class="row" style="justify-content:center">
    <div class="col-lg-6 col-md-8 mx-auto">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Nilai {{ $pembelian->ujian->nama }}</h6>
            </div>
            <div class="card-body px-3 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <tbody>
                            <tr>
                                <td style="text-align: right; width: 50%">
                                    <h6 class="mb-0 mr-6">Nama Ujian</h6>
                                </td>
                                <td>
                                    <p class="font-weight-bold mb-0">{{ $pembelian->ujian->nama }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    <h6 class="mb-0 mr-6">Email</h6>
                                </td>
                                <td>
                                    <p class="font-weight-bold mb-0">{{ auth()->user()->email }}</p>
                                </td>
                            </tr>
                                <td style="text-align: right">
                                    <h6 class="mb-0 mr-6">Waktu Selesai</h6>
                                </td>
                                <td>
                                    <p class="font-weight-bold mb-0">{{ \Carbon\Carbon::parse($pembelian->updated_at)->isoFormat('D MMMM Y HH:mm:ss') }}</p>
                                </td>
                            </tr>
                            </tr>
                                <td style="text-align: right">
                                    <h6 class="mb-0 mr-6">Status</h6>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-info">{{ $pembelian->status_pengerjaan }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right">
                                    <h6 class="mb-0 mr-6">Jumlah Benar</h6>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-success">{{ round($benar / $pembelian->ujian->jumlah_soal * 100, 2) }}</span>
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
