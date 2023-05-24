@php
$ada_jawaban = false;
@endphp
@extends('layouts.user.app')

@section('title')
{{ $soal[0]->soal->ujian->nama }}
@endsection

@section('content')
@foreach ($soal as $item)
<div class="d-flex justify-content-end">
    <h4><span id="timer" class="badge badge-sm bg-gradient-success">--:--:--</span></h4>
</div>
<div id="divReload">
    <div id="soal" class="col-12">
        <div class="card h-100">
            <div class="card-header pb-0 p-4">
                <div class="row">
                    <div class="d-flex align-items-center col-md-6">
                        <h6 class="mb-0">Nomor <span id="nomor" class="badge badge-sm bg-gradient-{{ $item->ragu_ragu == 0 ? 'info' : 'warning' }}">{{ $soal->currentPage() }}</span></h6>
                        <div class="form-check form-switch ps-0 mt-2 mx-3">
                            <input class="form-check-input ms-auto" onclick="storeRagu('{{ $item->id }}')" type="checkbox" {{ $item->ragu_ragu == 1 ? 'checked' : '' }} id="raguRagu">
                            <label class="form-check-label" for="raguRagu">Ragu-ragu</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <p class="text-sm mx-2">{!! $item->soal->soal !!}</p>
                <hr class="horizontal gray-light my-4">
                <ul class="list-group">
                    <form class="form" data-jawaban="{{ $item->soal->jawaban->count() }}" id="form" action="" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" id="key" class="key" name="key">
                        @foreach ($item->soal->jawaban as $key => $jawaban)
                        <li class="list-group-item border-0 ps-0 pt-0 mb-2">
                            <input type="hidden" name="jawaban_peserta" value="{{ $item->id }}">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if ($item->jawaban_id == null)
                                            <a type="button" name="button[]" id="button{{ $key }}" data-key="{{ $jawaban->id }}" class="btn mb-0 mx-2 ps-3 pe-3 py-2 button-jawaban">{{ chr($key + 65) }}</a>
                                            @else
                                                @if ($jawaban->id == $item->jawaban_id)
                                                <a type="button" name="button[]" id="button{{ $key }}" data-key="{{ $jawaban->id }}" class="btn bg-gradient-info mb-0 mx-2 ps-3 pe-3 py-2 button-jawaban">{{ chr($key + 65) }}</a>
                                                @else
                                                <a type="button" name="button[]" id="button{{ $key }}" data-key="{{ $jawaban->id }}" class="btn mb-0 mx-2 ps-3 pe-3 py-2 button-jawaban">{{ chr($key + 65) }}</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            {{ $jawaban->jawaban }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>
                        @endforeach
                    </form>
                </ul>
                @if($soal->currentPage() == $soal->total())
                <button type="submit" id="submit" class="btn bg-gradient-success float-end">Selesai</button>
                @endif
            </div>
        </div>
    </div>

    <div id="navigation" class="mt-4" style="text-align: center">
        @for ($i = 1; $i <= $soal->total(); $i++)
            @if ($i == $soal->currentPage())
            <a class="btn bg-gradient-info ps-3 pe-3 py-2">{{ $i }}</a>
            @else
                @if ($ragu_ragu[$i-1])
                    <a class="btn bg-gradient-warning ps-3 pe-3 py-2" onclick="fetch_data({{ $i }})">{{ $i }}</a>
                @else
                    <a class="btn btn-outline-info ps-3 pe-3 py-2" onclick="fetch_data({{ $i }})">{{ $i }}</a>
                @endif
            @endif
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

    let x = setInterval(function() {
        let now = new Date().getTime();
        let distance = countDownDate - now;

        let hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
        let minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
        let seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');

        document.getElementById("timer").innerHTML = hours + ":" +
            minutes + ":" + seconds;
        if (distance < 0) {
            clearInterval(x);
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
        }
    }, 1000);

    $(function() {
        $(document).on('click', ".button-jawaban", function(){
            var data = $.parseJSON($(this).attr('data-key'));
            document.getElementById("key").value = data;
            var jawaban = data;
            var jml_jawaban = $('#form').attr('data-jawaban');
            $.post('{{ route('ujian.store') }}', $('#form').serialize())
                .done((response) => {
                    for (let i = 0; i < jml_jawaban; i++) {
                        let pilgan = $('#button' + i).attr('data-key');
                        if (pilgan == jawaban) {
                            document.getElementById("button" + i).className = "btn bg-gradient-info mb-0 mx-2 ps-3 pe-3 py-2 button-jawaban";
                        } else {
                            document.getElementById("button" + i).className = "btn mb-0 mx-2 ps-3 pe-3 py-2 button-jawaban";
                        }
                    }
                })
                .fail((errors) => {
                    return;
                });
        });
    });


    function storeRagu(jawaban_id) {
        event.preventDefault()
        let token = $("meta[name='csrf-token']").attr("content");
        console.log(jawaban_id);
        $.ajax({
            url: `/ujian/storeragu/` + jawaban_id
            , type: "put"
            , cache: false
            , data: {
                "_token": token
            }
            , success: function(response) {
                console.log('success')
                var raguRagu = document.getElementById("raguRagu")
                raguRagu.checked = !raguRagu.checked;
                if (raguRagu.checked) {
                    document.getElementById("nomor").className = "badge badge-sm bg-gradient-warning";
                } else {
                    document.getElementById("nomor").className = "badge badge-sm bg-gradient-info";
                }
            }
            , error: function(error) {
                console.log('error')
            }
        });
    }

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
