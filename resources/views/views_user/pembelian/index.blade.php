@extends('layouts.user.app')

@section('title')
{{ $pembelian->ujian->nama }}
@endsection

@section('breadcrumb')
@parent
<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Pembelian</li>
@endsection

@section('content')
<div class="row" style="justify-content:center">
    <div class="col-lg-6 col-md-8 mx-auto">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Transaksi #{{ sprintf('%06d', $pembelian->id); }}</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
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
                                <td>
                                    <p class="font-weight-bold mb-0">Rp{{ number_format( $pembelian->ujian->harga , 0 , ',' , '.' ) }}</p>
                                </td>
                            </tr>
                            </tr>
                                <td style="text-align: right">
                                    <h6 class="mb-0 mr-6">Status</h6>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-{{ $pembelian->status == 'Sukses' ? 'success' : ($pembelian->status == 'Pending' ? 'warning' : ($pembelian->status == 'Belum dibayar' ? 'primary' : 'danger')) }}">{{ $pembelian->status }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="text-align:right;">
                                    @if($pembelian->status == 'Gagal')
                                    <form method="post" action="{{ route('pembelian.store') }}">
                                        @csrf
                                        @method('post')
                                        <input type="hidden" name="id_ujian" value="{{ $pembelian->ujian_id }}">
                                        <button type="submit" class="btn bg-gradient-info mt-4 mb-0">Ulangi Bayar</button>
                                    </form>
                                    @else
                                    <button type="button" id="pay" class="btn bg-gradient-info mt-4 mb-0">Bayar</button>
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
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

@if($pembelian->status != 'Gagal')

<script>
    const payButton = document.querySelector('#pay');
    payButton.addEventListener('click', function(e) {
        e.preventDefault();

        snap.pay('{{ $pembelian->kode_pembelian }}', {
            // Optional
            onSuccess: function(result) {
                alert('success');
                /* You may add your own js here, this is just example */
                // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                location.reload();
                console.log(result);
            },
            // Optional
            onPending: function(result) {
                /* You may add your own js here, this is just example */
                // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                location.reload();
                console.log(result)
            },
            // Optional
            onError: function(result) {
                /* You may add your own js here, this is just example */
                // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                location.reload();
                console.log(result)
            }
        });
    });
</script>
@endif
@endpush
