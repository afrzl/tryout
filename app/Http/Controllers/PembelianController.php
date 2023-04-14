<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\Pembelian;
use Illuminate\Http\Request;
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
        $cek = Pembelian::where('user_id', auth()->user()->id)->where('ujian_id', $request->id_ujian)->latest('updated_at')->first();

        if (!$cek) {
            $pembelian = new Pembelian();
            $pembelian->ujian_id = $request->id_ujian;
            $pembelian->user_id = auth()->user()->id;
            $pembelian->status = 'Belum dibayar';
            $pembelian->save();

            $id_pembelian = $pembelian->id;
        } else {
            if ($cek->status == 'Gagal') {
                $pembelian = new Pembelian();
                $pembelian->ujian_id = $request->id_ujian;
                $pembelian->user_id = auth()->user()->id;
                $pembelian->status = 'Belum dibayar';
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
        $pembelian = Pembelian::with('ujian', 'user')->find($id);
        if ($pembelian) {
            $snapToken = $pembelian->kode_pembelian;

            if ($pembelian->user_id === auth()->user()->id) {
                if (is_null($snapToken)) {
                    $midtrans = new CreateSnapTokenService($pembelian);
                    $snapToken = $midtrans->getSnapToken();

                    $pembelian->kode_pembelian = $snapToken;
                    $pembelian->update();
                }
                if ($pembelian->status == 'Sukses') {
                    return 'sajdbk';
                }
                return view('views_user.pembelian.index', compact('pembelian'));
            }
        }
        abort(403, 'Error');

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
    }
}
