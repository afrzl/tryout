@php
$ada_jawaban = false;
@endphp
@extends('layouts.user.app')

@section('title')
{{ $ujian->nama }}
@endsection

@section('content')
@foreach ($soal as $item)
<main id="main">
    <div class="container mb-5" style="margin-top: 124px">
        <div class="row">
            <div id="divReload" class="col-lg-9 mb-5">
                <div>
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="row">
                                <div class="d-flex flex-row">
                                    <div id="nomor" class="d-flex col-5 justify-content-between" x-data x-init="$store.getRagu.setRagu('{{ $item->ragu_ragu }}')">
                                        <div class="d-flex">
                                            <h6 class="mt-2">Nomor <span id="nomor" style="color: white !important" class="badge bg-success">{{ $soal->currentPage() }}</span></h6>
                                        </div>

                                    </div>
                                    @if($ujian->jenis_ujian == 'skd')
                                        <div id="jenisSoal" class="col-2 d-flex justify-content-center">
                                            <h4><span class="badge bg-secondary">{{ strtoupper($soal[0]->jenis_soal) }}</span></h4>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="soal" class="card-body p-3">
                            <p class="text-sm mx-2">{!! $item->soal !!}</p>
                            <hr class="horizontal gray-light my-4">
                            <ul class="list-group">
                            <div>
                                @foreach ($item->jawaban as $key => $jawaban)
                                <li class="list-group-item border-0 ps-0 pt-0 mb-2">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <button style="pointer-events: none" type="button" class="btn mb-0 mx-2 ps-3 pe-3 py-2 {{ $jawaban->id == $soal[0]->kunci_jawaban ? 'btn-primary' : 'btn-outline-primary' }}">{{ chr($key + 65) }}</button>
                                                </td>
                                                <td>
                                                    {!! $jawaban->jawaban !!}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                                @endforeach
                            </div>
                            </ul>
                            <div class="mt-2 alert alert-secondary" role="alert">
                                <p><b>Pembahasan: </b></p>
                                {!! $item->pembahasan !!}
                            </div>
                        </div>
                        <div id="skeleton" class="card-body p-3">
                            <p class="card-title placeholder-glow">
                                <span class="placeholder col-12 placeholder-lg"></span>
                            </p>
                            <p class="card-title placeholder-glow">
                                <span class="placeholder col-12 placeholder-lg"></span>
                            </p>
                            <p class="card-title placeholder-glow">
                                <span class="placeholder col-12 placeholder-lg"></span>
                            </p>
                            <p class="card-title placeholder-glow">
                                <span class="placeholder col-8 placeholder-lg"></span>
                            </p>
                            <hr class="horizontal gray-light my-4">
                            <ul class="list-group">
                            <div>
                                @foreach ($item->jawaban as $key => $jawaban)
                                <div class="mb-3">
                                    <a type="button" class="btn mb-0 mx-2 ps-3 pe-3 py-2 button-jawaban btn-outline-primary">{{ chr($key + 65) }}</a>
                                    <span style="font-size: 20px" class="placeholder-glow">
                                        <span class="placeholder col-6"></span>
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            </ul>
                        </div>
                        <div id="divFoot" class="card-footer">
                            <div class="d-flex justify-content-between">
                                <button type="button" @if($soal->currentPage() != 1) onclick="fetch_data({{ $soal->currentPage() - 1 }})" @else disabled @endif class="btn btn-primary">Sebelumnya</button>
                                @if($ujian->tampil_poin)
                                    <button class="btn btn-secondary">
                                        @if($soal[0]->jenis_soal == 'tkp')
                                            Poin : 1 - 5
                                        @else
                                            Benar: {{ $soal[0]->poin_benar }}, Salah: {{ $soal[0]->poin_salah }}, Kosong: {{ $soal[0]->poin_kosong }}
                                        @endif
                                    </button>
                                @endif
                                <button type="button" @if($soal->currentPage() != $soal->total()) onclick="fetch_data({{ $soal->currentPage() + 1 }})" @else disabled @endif class="btn btn-primary">Berikutnya</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div id="navigation" class="col-lg-3" style="text-align: center">
                <div class="card">
                    <div class="card-header">
                        <span>Navigasi Soal</span>
                    </div>
                    <div class="card-body">
                        <div id="divNomor">
                            @for ($i = 1; $i <= $soal->total(); $i++)
                                <a id="nav_{{ $i }}" x-data class="btn btn-nav mb-2" style="width: 53px; font-size: 15px" :class="{{ $i }} == {{ $soal->currentPage() }} ? 'btn-primary' : 'btn-outline-primary'" @if($i != $soal->currentPage()) onclick="fetch_data({{ $i }})" @endif>{{ $i }}</a>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endforeach
@endsection

@push('scripts')
<script src="{{ asset('adminLTE') }}/plugins/moment/moment.min.js" defer></script>
<script id="storeJawaban">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#skeleton').hide();

    function fetch_data(page) {
        let l = window.location;
        $('#skeleton').show();
        $('#soal').hide();

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        let soalNow = urlParams.get('no');

        $.ajax({
            url: l.origin + l.pathname + "?no=" + page,
            success: function(satwork) {
                let doc = document.createElement('html');
                doc.innerHTML = satwork;
                let div = doc.querySelector('#divReload');
                let nomor = doc.querySelector('#nomor');
                let soal = div.querySelector('#soal');
                $('#nomor').replaceWith(nomor);
                $('#soal').replaceWith(soal);
                $('#nav_' + page).replaceWith(doc.querySelector('#nav_' + page));
                $('#nav_' + soalNow).replaceWith(doc.querySelector('#nav_' + soalNow));
                $('#divFoot').replaceWith(doc.querySelector('#divFoot'));
                @if($ujian->jenis_ujian == 'skd')
                $('#jenisSoal').replaceWith(doc.querySelector('#jenisSoal'));
                @endif

                $('#skeleton').hide();
                $('#soal').show();

                window.location.href = '#';
                const url = new URL(window.location.href);
                url.searchParams.set('no', page);
                window.history.replaceState(null, null, url);
            },
            error: function() {
                window.location.href = '/';
            }
        });
    }

</script>
@endpush
