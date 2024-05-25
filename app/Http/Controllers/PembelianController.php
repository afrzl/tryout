<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
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
        $id = explode('-', request()->order_id);
        $order_id = $id[0];
        return redirect()->route('pembelian.show', $order_id);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    function beliPaket($paket_id, $himada_id = null)
    {
        $cek = Pembelian::where('user_id', auth()->user()->id)
            ->where('paket_id', $paket_id)
            ->latest('updated_at')
            ->first();
        $paketUjian = PaketUjian::find($paket_id);

        if (
            !Carbon::now()->between(
                $paketUjian->waktu_mulai,
                $paketUjian->waktu_akhir
            )
        ) {
            abort(403, 'Waktu pendaftaran sudah selesai');
        }

        if (!$cek) {
            $pembelian = new Pembelian();
            $pembelian->paket_id = $paket_id;
            $pembelian->user_id = auth()->user()->id;
            $pembelian->status =
                $paketUjian->harga == 0 ? 'Sukses' : 'Belum dibayar';
            $pembelian->harga = $paketUjian->harga;

            //kalo udah beli tobar batch 1
            if ($paketUjian->id == '0df8c9b0-d352-448b-9611-abadffc4f46d') {
                $tobar = Pembelian::where('user_id', auth()->user()->id)
                    ->where('paket_id', '33370256-b734-470a-afe9-c7ca8421f1b3')
                    ->where('status', 'Sukses')
                    ->latest('updated_at')
                    ->first();
                if ($tobar) {
                    $pembelian->harga = $paketUjian->harga - $tobar->harga;
                }
            }

            $pembelian->save();
            $id_pembelian = $pembelian->id;
        } else {
            if ($cek->status == 'Gagal') {
                $pembelian = new Pembelian();
                $pembelian->paket_id = $paket_id;
                $pembelian->user_id = auth()->user()->id;
                $pembelian->status =
                    $paketUjian->harga == 0 ? 'Sukses' : 'Belum dibayar';
                $pembelian->harga = $paketUjian->harga;
                //kalo udah beli tobar batch 1
                if ($paketUjian->id == '0df8c9b0-d352-448b-9611-abadffc4f46d') {
                    $tobar = Pembelian::where('user_id', auth()->user()->id)
                        ->where(
                            'paket_id',
                            '33370256-b734-470a-afe9-c7ca8421f1b3'
                        )
                        ->where('status', 'Sukses')
                        ->latest('updated_at')
                        ->first();
                    if ($tobar) {
                        $pembelian->harga = $paketUjian->harga - $tobar->harga;
                    }
                }

                $pembelian->save();

                $id_pembelian = $pembelian->id;
            } else {
                $id_pembelian = $cek->id;
            }
        }

        if ($himada_id) {
            $pembelian = Pembelian::findOrFail($id_pembelian);
            $voucher = Voucher::findOrFail($himada_id);
            if ($pembelian->voucher_id != null) {
                $pembelian->harga += $pembelian->voucher->diskon;
            }
            $pembelian->voucher_id = $himada_id;
            $pembelian->harga -= $voucher->diskon;

            $pembelian->save();
        }

        $check = false;
        $user = \App\Models\User::with([
            'usersDetail' => function ($query) {
                $query
                    ->where('no_hp', '!=', null)
                    ->where('provinsi', '!=', null)
                    ->where('kabupaten', '!=', null)
                    ->where('kecamatan', '!=', null)
                    ->where('asal_sekolah', '!=', null)
                    ->where('sumber_informasi', '!=', null)
                    ->where('prodi', '!=', null)
                    ->where('penempatan', '!=', null)
                    ->where('instagram', '!=', null);
            },
        ])
            ->where('id', auth()->user()->id)
            ->first();
        if ($user->usersDetail) {
            $check = true;
        }

        if (!$check) {
            return redirect()
                ->route('profile.show')
                ->with([
                    'message' => 'Silahkan lengkapi profil terlebih dahulu.',
                    'id_pembelian' => $id_pembelian,
                ]);
        }

        return redirect()->route('pembelian.show', $id_pembelian);
    }

    public function tonas($voucher = null)
    {
        $himada = Voucher::where('kode', $voucher)->first();
        if ($himada) {
            return $this->beliPaket(
                'd5f57505-fb5a-4f59-a301-3722ef581844',
                $himada->id
            );
        } else {
            return $this->beliPaket(
                'd5f57505-fb5a-4f59-a301-3722ef581844',
                null
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->beliPaket($request->paket_id);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pembelian = Pembelian::with(
            'paketUjian',
            'user',
            'voucher'
        )->findOrFail($id);
        if ($pembelian->user_id === auth()->user()->id) {
            return view('views_user.pembelian.index', compact('pembelian'));
        }
        abort(403, 'Error');
    }

    public function applyVoucher(Request $request)
    {
        $pembelian = Pembelian::findOrFail($request->id);
        if (!$pembelian) {
            return response()->json('Invalid.', 300);
        }

        if ($pembelian->voucher_id == null) {
            $voucher = Voucher::where('kode', $request->voucher)
                ->where('paket_ujian_id', $pembelian->paket_id)
                ->first();
            if (!$voucher) {
                return response()->json('Voucher invalid.', 300);
            }
            if ($voucher->himada_id != null || $voucher->kuota > 0) {
                $voucher->kuota =
                    $voucher->himada_id == null ? $voucher->kuota - 1 : 0;
                $voucher->update();
                $pembelian->voucher_id = $voucher->id;
                $pembelian->harga -= $voucher->diskon;
                $pembelian->update();

                return response()->json('Voucher berhasil dipakai.', 200);
            } else {
                return response()->json('Voucher sudah habis.', 300);
            }
        } else {
            $voucher = Voucher::findOrFail($pembelian->voucher_id);
            $pembelian->voucher_id = null;
            $pembelian->harga += $voucher->diskon;
            $pembelian->update();

            $voucher->kuota =
                $voucher->himada_id == null ? $voucher->kuota + 1 : 0;
            $voucher->update();

            return response()->json('Voucher berhasil dicancel.', 200);
        }

        return response()->json('Voucher invalid.', 300);
    }

    public function pay(Request $request)
    {
        $pembelian = Pembelian::findOrFail($request->id);
        $snapToken = $pembelian->kode_pembelian;
        $total = 0;
        $item_details[] = [
            'id' => $pembelian->paketUjian->id,
            'price' => $pembelian->harga,
            'quantity' => 1,
            'name' => $pembelian->paketUjian->nama,
        ];
        $total += $pembelian->harga;

        if ($request->metode == 'bank-transfer') {
            $item_details[] = [
                'id' => 999999,
                'price' => 4500,
                'quantity' => 1,
                'name' => 'Biaya admin',
            ];
            $total += 4500;
        }

        $midtrans = new CreateSnapTokenService(
            $pembelian,
            $request->metode,
            $item_details,
            $total
        );
        $snapToken = $midtrans->getSnapToken($request->metode_pembayaran);

        $pembelian->kode_pembelian = $snapToken;
        $pembelian->update();

        return response()->json(
            [
                'snapToken' => $snapToken,
                'metode' => $request->metode,
            ],
            200
        );

        if (is_null($snapToken)) {
        } else {
            return response()->json(
                [
                    'snapToken' => $snapToken,
                    'metode' => $request->metode,
                ],
                200
            );
        }
        return response()->json(
            'Tidak dapat membayar, silahkan hubungi admin.',
            300
        );
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
        $hashed = hash(
            'sha512',
            $request->order_id .
                $request->status_code .
                $request->gross_amount .
                $serverKey
        );

        $id = explode('-', $request->order_id);
        $order_id = $id[0];
        if ($hashed == $request->signature_key) {
            if (
                $request->transaction_status == 'capture' ||
                $request->transaction_status == 'settlement'
            ) {
                $pembelian = Pembelian::find($order_id);
                $pembelian->status = 'Sukses';
                $pembelian->jenis_pembayaran = $request->payment_type;
                $pembelian->update();
                $user_id = $pembelian->user_id;

                if (
                    $pembelian->paket_id ==
                    '0df8c9b0-d352-448b-9611-abadffc4f46d'
                ) {
                    $bundling = Pembelian::where(
                        'paket_id',
                        '33370256-b734-470a-afe9-c7ca8421f1b3'
                    )
                        ->where('user_id', $user_id)
                        ->where('status', 'Sukses')
                        ->first();
                    if (!$bundling) {
                        $pembelian = new Pembelian();
                        $pembelian->paket_id =
                            '33370256-b734-470a-afe9-c7ca8421f1b3';
                        $pembelian->user_id = $user_id;
                        $pembelian->status = 'Sukses';
                        $pembelian->harga = 0;
                        $pembelian->jenis_pembayaran = 'Bundling';
                        $pembelian->save();
                    }
                }

                if (
                    $pembelian->paket_id ==
                    '33370256-b734-470a-afe9-c7ca8421f1b3'
                ) {
                    $bundling = Pembelian::where(
                        'paket_id',
                        '981ae5b5-a48d-47e6-9cc7-9e79994a3ef0'
                    )
                        ->where('user_id', $user_id)
                        ->where('status', 'Sukses')
                        ->first();
                    if (!$bundling) {
                        $pembelian = new Pembelian();
                        $pembelian->paket_id =
                            '981ae5b5-a48d-47e6-9cc7-9e79994a3ef0';
                        $pembelian->user_id = $user_id;
                        $pembelian->status = 'Sukses';
                        $pembelian->harga = 0;
                        $pembelian->jenis_pembayaran = 'Bundling';
                        $pembelian->save();
                    }
                }

                if (
                    $pembelian->paket_id ==
                    '981ae5b5-a48d-47e6-9cc7-9e79994a3ef0'
                ) {
                    $bundling = Pembelian::where(
                        'paket_id',
                        '0be570c6-7edf-4970-bd99-304d0626f9ff'
                    )
                        ->where('user_id', $user_id)
                        ->where('status', 'Sukses')
                        ->first();
                    if (!$bundling) {
                        $pembelian = new Pembelian();
                        $pembelian->paket_id =
                            '0be570c6-7edf-4970-bd99-304d0626f9ff';
                        $pembelian->user_id = $user_id;
                        $pembelian->status = 'Sukses';
                        $pembelian->harga = 0;
                        $pembelian->jenis_pembayaran = 'Bundling';
                        $pembelian->save();
                    }
                }
            } elseif (
                $request->transaction_status == 'deny' ||
                $request->transaction_status == 'cancel' ||
                $request->transaction_status == 'expire'
            ) {
                $pembelian = Pembelian::find($order_id);
                $pembelian->status =
                    $pembelian->status == 'Sukses'
                        ? $pembelian->status
                        : 'Gagal';
                $pembelian->update();
            }
        }
    }
}
