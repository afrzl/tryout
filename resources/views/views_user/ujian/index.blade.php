@php
$ada_jawaban = false;
@endphp
@extends('layouts.user.app')

@section('title')
{{ $soal[0]->soal->ujian->nama }}
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
                                            <h6 class="mt-2">Nomor <span id="nomor" style="color: white !important" class="badge" :class="$store.getRagu.ragu ? 'bg-warning' : 'bg-success'">{{ $soal->currentPage() }}</span></h6>
                                            <div class="form-check form-switch ps-0 mt-2 mx-3">
                                                <input class="form-check-input ms-auto" @click="$store.getRagu.toggle({{ $item->id }})" type="checkbox" :checked="$store.getRagu.ragu ? 'isChecked' : ''">
                                                <label class="form-check-label" for="raguRagu">Ragu-ragu</label>
                                            </div>
                                        </div>

                                    </div>
                                    @if($soal[0]->soal->ujian->jenis_ujian == 'skd')
                                        <div id="jenisSoal" class="col-2 d-flex justify-content-center">
                                            <h4><span class="badge bg-secondary">{{ strtoupper($soal[0]->soal->jenis_soal) }}</span></h4>
                                        </div>
                                    @endif
                                    <div class="{{ $soal[0]->soal->ujian->jenis_ujian == 'skd' ? 'col-5' : 'col-7' }} d-flex justify-content-end" x-data="countdown()" x-init="init()">
                                        <h4 class="-mb-1"><span x-text="getTime()" class="badge badge-primary">00:00:00</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="soal" class="card-body p-3">
                            {{-- <h4>{{ $item->soal->jenis_soal }}</h4> --}}
                            <p class="text-sm mx-2">{!! $item->soal->soal !!}</p>
                            <hr class="horizontal gray-light my-4">
                            <ul class="list-group">
                            <div x-data x-init="$store.getJawaban.setJawaban('{{ $item->jawaban_id }}')">
                                <form class="form" id="form" action="" method="post">
                                    @csrf
                                    @method('post')
                                    <input type="hidden" x-model="$store.getJawaban.jawaban" id="key" class="key" name="key">
                                    <input type="hidden" name="jawaban_peserta" value="{{ $item->id }}">
                                </form>
                                @foreach ($item->soal->jawaban as $key => $jawaban)
                                <li class="list-group-item border-0 ps-0 pt-0 mb-2">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a type="button" @click="
                                                        $store.getJawaban.setJawaban('{{ $jawaban->id }}');
                                                        $nextTick(() => { $store.getJawaban.storeJawaban({{ $jawaban->id }}) });
                                                        " class="btn mb-0 mx-2 ps-3 pe-3 py-2 button-jawaban" :class="{{ $jawaban->id }} == $store.getJawaban.jawaban ? 'btn-primary' : 'btn-outline-primary'">{{ chr($key + 65) }}</a>
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
                            <div x-data x-init="$store.getJawaban.setJawaban('{{ $item->jawaban_id }}')">
                                <form class="form" id="form" action="" method="post">
                                    @csrf
                                    @method('post')
                                    <input type="hidden" x-model="$store.getJawaban.jawaban" id="key" class="key" name="key">
                                    <input type="hidden" name="jawaban_peserta" value="{{ $item->id }}">
                                </form>
                                @foreach ($item->soal->jawaban as $key => $jawaban)
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
                                @if($soal[0]->soal->ujian->tampil_poin)
                                    <button class="btn btn-secondary">
                                        @if($soal[0]->soal->jenis_soal == 'tkp')
                                            Poin : 1 - 5
                                        @else
                                            Benar: {{ $soal[0]->soal->poin_benar }}, Salah: {{ $soal[0]->soal->poin_salah }}, Kosong: {{ $soal[0]->soal->poin_kosong }}
                                        @endif
                                    </button>
                                @endif
                                @if($soal->currentPage() == $soal->total())
                                    <button type="submit" onclick="submit()" class="btn btn-danger float-end">Selesai</button>
                                @else
                                    <button type="button" @if($soal->currentPage() != $soal->total()) onclick="fetch_data({{ $soal->currentPage() + 1 }})" @endif class="btn btn-primary">Berikutnya</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div id="navigation" class="col-lg-3" style="text-align: center">
                <div class="d-grid">
                    <button type="submit" onclick="submit()" class="btn btn-danger float-end mb-3">Selesai</button>
                </div>
                <div class="card">
                    <div class="card-header">
                        <span>Navigasi Soal</span>
                    </div>
                    <div class="card-body">
                        <div id="divNomor">
                            @for ($i = 1; $i <= $soal->total(); $i++)
                                <a id="nav_{{ $i }}" x-data class="btn btn-nav mb-2 {{ ($i == $soal->currentPage() ? 'btn-primary' : ($ragu_ragu[$i-1] ? 'btn-warning' : (!empty($rekap_jawaban[$i-1]) ? 'btn-success' : 'btn-outline-primary' ))) }}" style="width: 53px; font-size: 15px" @if($i != $soal->currentPage()) onclick="fetch_data({{ $i }})" @endif>{{ $i }}</a>
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

    function countdown() {
        let countDownDate = new Date("{{ $ujianUser->waktu_akhir }}");
        return {
            countdown: '',
            distance: null,
            init() {
                setInterval(() => {
                    let now = new Date().getTime();
                    this.distance = countDownDate - now;

                    hours = String(Math.floor((this.distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                    minutes = String(Math.floor((this.distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                    seconds = String(Math.floor((this.distance % (1000 * 60)) / 1000)).padStart(2, '0');
                    this.countdown = hours + ":" + minutes + ":" + seconds;
                }, 1000);
            },
            getTime() {
                if (this.distance < 0) {
                    const ujian_user_id = '{{ $ujianUser->id }}'
                    let token = "{{ csrf_token() }}";
                    $.ajax({
                        url: `/ujian/selesaiujian/` + '{{ $soal[0]->ujian_user_id }}',
                        type: "put",
                        cache: false,
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            console.log('success');
                            window.location.href = `/tryout/` + '{{ $soal[0]->soal->ujian_id }}' + '/nilai';
                        },
                        error: function(error) {
                            console.log('error')
                            document.getElementById("timer").innerHTML = "EXPIRED";
                        }
                    });
                } else {
                    return this.countdown
                }
            },
        }
    }

    document.addEventListener('alpine:init', () => {
        Alpine.store('getRagu', {
            ragu: false,

            setRagu(initRagu) {
                this.ragu = (initRagu == '1' ? true : false)
            },

            toggle(id) {
                let token = "{{ csrf_token() }}";
                $.ajax({
                    url: `/ujian/storeragu/` + id
                    , type: "put"
                    , cache: false
                    , data: {
                        "_token": token
                    }
                }).done(response => {
                    this.ragu = ! this.ragu
                });
            }
        })

        Alpine.store('getJawaban', {
            jawaban: 0,

            setJawaban(initJawaban) {
                this.jawaban = initJawaban
            },

            storeJawaban(id) {
                $.post('{{ route('ujian.store') }}', $('#form').serialize())
                .done((response) => {
                    console.log(response);
                    toastr.success('Jawaban berhasil disimpan.');
                })
                .fail((errors) => {
                    toastr.error('Tidak tersambung dengan server.');
                    return;
                });
            }
        })
    })

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
                @if($soal[0]->soal->ujian->jenis_ujian == 'skd')
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

    function submit() {
        Swal.fire({
            title: 'Apakah kamu yakin akan mengakhiri ujian?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
            }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route('ujian.selesai', $soal[0]->ujian_user_id) }}', {
                    '_token': "{{ csrf_token() }}",
                    '_method': 'put'
                })
                .done((response) => {
                    window.location.href = `/tryout/` + '{{ $soal[0]->soal->ujian_id }}' + '/nilai';
                })
                .fail((response) => {
                    toastr.error('Tidak dapat menghapus data.');
                    return;
                })
            }
        })
    }

</script>
@endpush
