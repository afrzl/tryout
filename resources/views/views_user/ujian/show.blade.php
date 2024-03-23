@extends('layouts.user.app')

@section('title')
{{ $ujian->nama }}
@endsection

@section('content')
<main id="main">
    <div class="container mb-4" style="margin-top: 124px">
        <div class="row" style="justify-content:center">
            @if (!$betweenTime)
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert">
                        Waktu ujian telah berakhir.
                    </div>
                </div>
            @endif
            @if ($ujian->tampil_kunci == 3 && \Carbon\Carbon::now() < $ujian->waktu_pengumuman)
            <div class="col-lg-12">
                <div class="alert alert-warning" role="alert">
                    Pembahasan akan tampil pada {{ Carbon\Carbon::parse($ujian->waktu_pengumuman)->isoFormat('D MMMM Y HH:mm:ss') }}
                </div>
            </div>
            @endif
            @if($ujian->tipe_ujian == 2)
                <div class="col-lg-12">
                    <div class="alert alert-warning" role="alert">
                        Ujian bersifat periodik. Nilai yang terekap hanya pengerjaan pada saat pertama kali saja.
                    </div>
                </div>
            @endif
            <div class="col-lg-8 mb-3">
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title"><b>{{ $ujian->nama }}</b></h3>
                        <h6 class="card-subtitle mb-2 text-muted">Deskripsi ujian</h6>
                        {!! $ujian->deskripsi !!}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Peraturan Ujian</h5>
                        {!! $ujian->peraturan !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Peserta dan Tryout</h5>
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
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Durasi</h6>
                                        <span class="badge badge-primary">{{ $ujian->lama_pengerjaan }} menit</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Waktu Mulai Ujian</h6>
                                        <span>{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->isoFormat('D MMMM Y HH:mm:ss') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6 style="font-size: 80%" class="card-subtitle mb-1 text-muted">Waktu Selesai Ujian</h6>
                                        <span>{{ \Carbon\Carbon::parse($ujian->waktu_akhir)->isoFormat('D MMMM Y HH:mm:ss') }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-grid gap-2">
                            @if((($ujian->tampil_kunci == 1) && (isset($ujian->ujianUser[0]->status) == 2)) || ($ujian->tampil_kunci == 2 && \Carbon\Carbon::now() > $ujian->waktu_akhir ) || ($ujian->tampil_kunci == 3 && \Carbon\Carbon::now() > $ujian->waktu_pengumuman ))
                                <a href="/tryout/{{ $ujian->id }}/pembahasan?no=1" class="btn btn-primary" type="button">Pembahasan</a>
                            @endif
                            <button id="kerjakan" onclick="kerjakan('{{ $ujian->id }}')" class="btn btn-success {{ !$betweenTime ? 'disabled' : '' }}" type="button">Kerjakan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function kerjakan(id) {
        let token = $("meta[name='csrf-token']").attr("content");
        $('#kerjakan').html('Memuat soal <div class="spinner-border spinner-border-sm" role="status"></div>');
        $.ajax({
            url: `/ujian/mulaiujian/` + id,
            type: "put",
            cache: false,
            data: {
                "_token": "{{ csrf_token() }}"
            }
        }).done(res => {
            console.log(res)
            const response = res.split('|');
            if (response[0] == 'session limit') {
                Swal.fire({
                    icon: 'error',
                    text: 'Anda terdeteksi menggunakan lebih dari 2 perangkat. Silahkan tekan tombol logout dari semua perangkat untuk memulai ujian!',
                    confirmButtonText: 'Logout dari semua perangkat',
                }).then((result) => {
                    $.ajax({
                        url: `{{ route('session_destroy')}}`,
                        type: "get",
                        cache: false
                    }).then(response => {
                        if (response == 200) {
                            kerjakan(id);
                        }
                    })
                });
            } else if (response[0] == 'telah dikerjakan') {
                window.location.href = `/tryout/nilai/${response[1]}`;
            } else if (response[0] == 'OK') {
                window.location.href = `/ujian/${response[1]}?no=1`;
            }
        }).fail(response => {
            console.log(response);
        });
    }
</script>
@endpush
