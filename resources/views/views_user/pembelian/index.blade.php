@extends('layouts.user.app')

@section('title')
{{ $pembelian->paketUjian->nama }}
@endsection

@section('content')
<main id="main">
    <div class="container" style="margin-top: 124px">
        <div class="row" style="justify-content:center">
            <div class="col-lg-6 col-md-8 mx-auto">
                @if ($pembelian->status == 'Sukses')
                <div class="alert alert-success">
                    <b>Pembelian Berhasil!</b> <br>
                    Silakan join grup WA Peserta TONAS 2024 melalui tombol di bawah
                </div>
                @endif
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Transaksi <b>#{{ sprintf('%06d', $pembelian->id) }}</b></h6>
                    </div>
                    <div id="transaction" class="card-body px-3 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <tbody>
                                    <tr>
                                        <td style="text-align: right; width: 50%">
                                            <h6 class="mb-0 mr-6">Nama Paket Ujian</h6>
                                        </td>
                                        <td>
                                            <p class="font-weight-bold mb-0">{{ $pembelian->paketUjian->nama }}</p>
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
                                            <h6 class="mb-0 mr-6">Tanggal Pembelian</h6>
                                        </td>
                                        <td>
                                            <p class="font-weight-bold mb-0">{{ \Carbon\Carbon::parse($pembelian->created_at)->isoFormat('D MMMM Y HH:mm:ss') }}</p>
                                        </td>
                                    </tr>
                                    </tr>
                                        <td style="text-align: right">
                                            <h6 class="mb-0 mr-6">Harga</h6>
                                        </td>
                                        <td id="harga">
                                            <p class="font-weight-bold mb-0">Rp{{ number_format( $pembelian->harga , 0 , ',' , '.' ) }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right">
                                            <h6 class="mb-0 mr-6">Status</h6>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $pembelian->status == 'Sukses' ? 'success' : ($pembelian->status == 'Pending' ? 'warning' : ($pembelian->status == 'Belum dibayar' ? 'primary' : 'danger')) }}">{{ $pembelian->status }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right">
                                            <h6 class="mt-2 mr-6">Voucher</h6>
                                        </td>
                                        @if($pembelian->status == 'Sukses')
                                        <td>
                                            <span class="badge badge-primary">{{ $pembelian->voucher ? 'Rp' . number_format($pembelian->voucher->diskon, 0 , ',' , '.') : '' }}</span>
                                        </td>
                                        @else
                                        <form method="post" id="applyVoucher" action="{{ route('pembelian.applyVoucher') }}">
                                            @csrf
                                            @method('post')
                                            <td>
                                                <div class="row">
                                                    <div class="col-lg-9">
                                                        <input type="hidden" required name="id" value="{{ $pembelian->id }}">
                                                        <input type="text" @if($pembelian->voucher_id) @readonly(true) value="{{ $pembelian->voucher->kode }}" @endif class="form-control" id="voucher" name="voucher" val placeholder="Masukkan voucher disini">
                                                    </div>
                                                    @if($pembelian->status == 'Belum dibayar')
                                                    <div class="col-lg-3">
                                                        <button id="apply" type="submit" class="btn btn-{{ $pembelian->voucher_id ? 'danger' : 'primary' }}"><i class="fa fa-{{ $pembelian->voucher_id ? 'times' : 'check' }}"></i></button>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div id="deskripsi">
                                                    @if($pembelian->voucher_id)
                                                    @if($pembelian->voucher->diskon > 0)
                                                    <span style="color: green; font-size: 12px">Anda mendapatkan diskon Rp{{ number_format( $pembelian->voucher->diskon , 0 , ',' , '.' ) }}</span>
                                                    @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </form>
                                        @endif

                                    </tr>
                                    <tr>
                                        <td style="text-align: right">
                                            <h6 class="mb-0 mr-6">Metode Pembayaran</h6>
                                        </td>
                                        <td>
                                            @if($pembelian->status != 'Sukses')
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="metode_pembayaran1" value="other-qris" checked>
                                                <label class="form-check-label" for="metode_pembayaran1">
                                                    QRIS
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="metode_pembayaran2" value="bank-transfer">
                                                <label class="form-check-label" for="metode_pembayaran2">
                                                    Transfer Bank (+ Admin 4500)
                                                </label>
                                            </div>
                                            @else
                                            <p class="font-weight-bold mb-0">{{ $pembelian->jenis_pembayaran }}</p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="text-align:right;">
                                            @if($pembelian->status == 'Gagal')
                                            <form method="post" action="{{ route('pembelian.store') }}">
                                                @csrf
                                                @method('post')
                                                <input type="hidden" name="paket_id" value="{{ $pembelian->paket_id }}">
                                                <button type="submit" class="btn btn-warning mt-4 mb-0">Ulangi Bayar</button>
                                            </form>
                                            @elseif($pembelian->status == 'Sukses')
                                                @if ($pembelian->paket_id == 'd5f57505-fb5a-4f59-a301-3722ef581844')
                                                <a target="_blank" href="https://chat.whatsapp.com/CKsDXB9OJYZFWfxlYk5QPH" type="button" class="btn btn-success mt-4 mb-0">Grup WA</a>
                                                @elseif($pembelian->paket_id == '0df8c9b0-d352-448b-9611-abadffc4f46d')
                                                <a target="_blank" href="https://chat.whatsapp.com/GQefjygQnl82v9OlXwpPbL" type="button" class="btn btn-success mt-4 mb-0">Grup WA</a>
                                                @else
                                                <a target="_blank" href="https://chat.whatsapp.com/BzxL0RHOfXd1QhukyYzXTz" type="button" class="btn btn-success mt-4 mb-0">Grup WA</a>
                                                @endif
                                                <a href="{{ route('tryout.index', $pembelian->paket_id) }}" type="button" class="btn btn-primary mt-4 mb-0">Mulai Tryout</a>
                                            @else
                                            <button type="button" onClick="pay()" class="btn btn-success mt-4 mb-0">Bayar</button>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
@if($pembelian->status != 'Gagal')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const payButton = document.querySelector('#pay');
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    function pay() {
        $.post('{{ route('pembelian.pay') }}',
        {
            "_token": "{{ csrf_token() }}",
            _method: 'post',
            id: '{{ $pembelian->id }}',
            metode: document.querySelector('input[name="metode_pembayaran"]:checked').value,
        })
        .done((response) => {
            console.log(response);
            @if (env("MIDTRANS_TIPE") == 'production')
            window.location.href = `https://app.midtrans.com/snap/v3/redirection/${response.snapToken}#/${response.metode}`;
            @else
            window.location.href = `https://app.sandbox.midtrans.com/snap/v3/redirection/${response.snapToken}#/${response.metode}`;
            @endif
        })
        .fail((response) => {
            toastr.options = {"positionClass": "toast-bottom-right"};
            toastr.error('Tidak dapat membayar, silahkan hubungi admin.');
            return;
        })
    }

    $(function () {
        $('#applyVoucher').on('submit', function(e) {
            if (! e.preventDefault()) {
                $.post($('#applyVoucher').attr('action'), $('#applyVoucher').serialize())
                .done((response) => {
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.success(response);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                })
                .fail((response) => {
                    toastr.options = {"positionClass": "toast-bottom-right"};
                    toastr.error(response.responseJSON);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                    return;
                });
                }
            }
        )
    })
</script>
@endif
@endpush
