<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Pembelian;
use App\Models\PaketUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Midtrans\CreateSnapTokenService;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cek = Pembelian::where('user_id', auth()->user()->id)
                ->where('paket_id', $request->paket_id)
                ->latest('updated_at')
                ->first();
        $paketUjian = PaketUjian::find($request->paket_id);

        if (!$cek) {
            $pembelian = new Pembelian();
            $pembelian->paket_id = $request->paket_id;
            $pembelian->user_id = auth()->user()->id;
            $pembelian->status = $paketUjian->harga == 0 ? 'Sukses' : 'Belum dibayar';
            $pembelian->harga = $paketUjian->harga;
            $pembelian->save();

            $id_pembelian = $pembelian->id;
        } else {
            if ($cek->status == 'Gagal') {
                $pembelian = new Pembelian();
                $pembelian->paket_id = $request->paket_id;
                $pembelian->user_id = auth()->user()->id;
                $pembelian->status = $paketUjian->harga == 0 ? 'Sukses' : 'Belum dibayar';
                $pembelian->harga = $paketUjian->harga;
                $pembelian->save();

                $id_pembelian = $pembelian->id;
            } else {
                $id_pembelian = $cek->id;
            }
        }

        return redirect()->route('pembelian.show', $id_pembelian);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pembelian = Pembelian::with('paketUjian', 'user', 'voucher')->findOrFail($id);
        if ($pembelian->user_id === auth()->user()->id) {
            return view('views_user.pembelian.index', compact('pembelian'));
        }
        abort(403, 'Error');

    }

    public function applyVoucher(Request $request) {
        $pembelian = Pembelian::findOrFail($request->id);
        if (!$pembelian) {
            return response()->json('Invalid.', 300);
        }

        $voucher = Voucher::where('kode', $request->voucher)->first();
        if (!$voucher) {
            return response()->json('Voucher invalid.', 300);
        }

        if ($pembelian->voucher_id == NULL) {
            if ($voucher->himada_id != NULL || $voucher->kuota > 0) {
                $voucher->kuota = $voucher->himada_id == NULL ? $voucher->kuota - 1 : 0;
                $voucher->update();
                $pembelian->voucher_id = $voucher->id;
                $pembelian->harga -= $voucher->diskon;
                $pembelian->update();

                return response()->json('Voucher berhasil dipakai.', 200);
            } else {
                return response()->json('Voucher invalid.', 300);
            }
        } else {
            $pembelian->voucher_id = NULL;
            $pembelian->update();

            $voucher->kuota = $voucher->himada_id == NULL ? $voucher->kuota + 1 : 0;
            $voucher->update();

            return response()->json('Voucher berhasil dicancel.', 200);
        }

        return response()->json('Voucher invalid.', 300);
    }

    public function pay(Request $request) {
        $pembelian = Pembelian::findOrFail($request->id);
        $snapToken = $pembelian->kode_pembelian;
        if (is_null($snapToken)) {
            $midtrans = new CreateSnapTokenService($pembelian);
            $snapToken = $midtrans->getSnapToken();

            $pembelian->kode_pembelian = $snapToken;
            $pembelian->update();

            return response()->json($snapToken, 200);
        } else {
            return response()->json($snapToken, 200);
        }
        return response()->json('Tidak dapat membayar, silahkan hubungi admin.', 300);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembelian $pembelian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembelian $pembelian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        //
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $pembelian = Pembelian::find($request->order_id);
                $pembelian->status = 'Sukses';
                $pembelian->jenis_pembayaran = $request->payment_type;
                $pembelian->update();
            } else if ($request->transaction_status == 'deny' || $request->transaction_status == 'cancel' || $request->transaction_status == 'expire') {
                $pembelian = Pembelian::find($request->order_id);
                $pembelian->status = 'Gagal';
                $pembelian->update();
            }
        }
        else {
            $pembelian = Pembelian::find($request->order_id);
            $pembelian->status = 'Gagal';
            $pembelian->update();
        }
    }
}
