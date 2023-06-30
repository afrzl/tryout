@php
$ada_jawaban = false;
@endphp
@extends('layouts.user.app')

@section('title')
{{ $soal[0]->soal->ujian->nama }}
@endsection

@section('content')
@foreach ($soal as $item)
<div class="d-flex justify-content-end" x-data="countdown()" x-init="init()">
    <h4><span x-text="getTime()" class="badge badge-sm bg-gradient-success"></span></h4>
</div>
<div id="divReload">
    <div id="soal" class="col-12">
        <div class="card h-100">
            <div class="card-header pb-0 p-4">
                <div class="row">
                    <div class="d-flex align-items-center col-md-6" x-data x-init="$store.getRagu.setRagu('{{ $item->ragu_ragu }}')">
                        <h6 class="mb-0">Nomor <span id="nomor" class="badge badge-sm" :class="$store.getRagu.ragu ? 'bg-gradient-warning' : 'bg-gradient-info'">{{ $soal->currentPage() }}</span></h6>
                        <div class="form-check form-switch ps-0 mt-2 mx-3">
                            <input class="form-check-input ms-auto" @click="$store.getRagu.toggle({{ $item->id }})" type="checkbox" :checked="$store.getRagu.ragu ? 'isChecked' : ''">
                            <label class="form-check-label" for="raguRagu">Ragu-ragu</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <h4>{{ $item->soal->jenis_soal }}</h4>
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
                                            " class="btn mb-0 mx-2 ps-3 pe-3 py-2 button-jawaban" :class="{{ $jawaban->id }} == $store.getJawaban.jawaban ? 'bg-gradient-info' : ''">{{ chr($key + 65) }}</a>
                                    </td>
                                    <td>
                                        {{ $jawaban->jawaban }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </li>
                    @endforeach
                </div>
                </ul>
                @if($soal->currentPage() == $soal->total())
                <button type="submit" id="submit" class="btn bg-gradient-success float-end">Selesai</button>
                @endif
            </div>
        </div>
    </div>

    <div id="navigation" class="mt-4" style="text-align: center">
        @for ($i = 1; $i <= $soal->total(); $i++)
            <a x-data class="btn ps-3 pe-3 py-2" :class="{{ $i }} == {{ $soal->currentPage() }} ? 'bg-gradient-info' : {{ $ragu_ragu[$i-1] }} ? 'bg-gradient-warning' : 'bg-outline-info'" onclick="fetch_data({{ $i }})">{{ $i }}</a>
        @endfor
    </div>
</div>
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

    const split_pengerjaan = '{{ $soal[0]->soal->ujian->lama_pengerjaan }}'.split(':');
    let countDownDate = new Date("{{ $pembelian->waktu_mulai_pengerjaan }}");
    countDownDate.setHours(countDownDate.getHours() + parseInt(split_pengerjaan[0]));
    countDownDate.setMinutes(countDownDate.getMinutes() + parseInt(split_pengerjaan[1]));
    countDownDate.setSeconds(countDownDate.getSeconds() + parseInt(split_pengerjaan[2]));

    function countdown() {
        const split_pengerjaan = '{{ $soal[0]->soal->ujian->lama_pengerjaan }}'.split(':');
        let countDownDate = new Date("{{ $pembelian->waktu_mulai_pengerjaan }}");
        countDownDate.setHours(countDownDate.getHours() + parseInt(split_pengerjaan[0]));
        countDownDate.setMinutes(countDownDate.getMinutes() + parseInt(split_pengerjaan[1]));
        countDownDate.setSeconds(countDownDate.getSeconds() + parseInt(split_pengerjaan[2]));
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
                    const pembelian_id = '{{ $pembelian->id }}'
                    let token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        url: `/ujian/selesaiujian/` + pembelian_id
                        , type: "put"
                        , cache: false
                        , data: {
                            "_token": token
                        }
                        , success: function(response) {
                            console.log('success');
                            window.location.href = `/ujian/nilai/` + pembelian_id;
                        }
                        , error: function(error) {
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
                let token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: `/ujian/storeragu/` + id
                    , type: "put"
                    , cache: false
                    , data: {
                        "_token": token
                    }
                }).then(response => {
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
                .fail((errors) => {
                    return;
                });
            }
        })
    })

    function fetch_data(page) {
        let l = window.location;

        $.ajax({
            url: l.origin + l.pathname + "?page=" + page,
            success: function(satwork) {
                let doc = document.createElement('html');
                doc.innerHTML = satwork;
                let div = doc.querySelector('#divReload');
                let soal = div.querySelector('#soal');
                let navigation = div.querySelector('#navigation');
                $('#divReload').html(soal);
                $('#divReload').append(navigation);
                // $("#Soal").load(" #Soal > *");
            },
            error: function() {
                window.location.href = '/';
            }
        });
    }

    $(document).on('click', "#submit", function(){
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
                $.post('{{ route('ujian.selesai', $pembelian->id) }}', {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put'
                })
                .done((response) => {
                    window.location.href = `/ujian/nilai/` + {{ $pembelian->id }}
                })
                .fail((response) => {
                    toastr.error('Tidak dapat menghapus data.');
                    return;
                })
            }
        })
    });

</script>
@endpush
