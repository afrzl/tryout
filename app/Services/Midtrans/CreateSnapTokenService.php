<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected $pembelian;

    public function __construct($pembelian)
    {
        parent::__construct();

        $this->pembelian = $pembelian;
    }

    public function getSnapToken()
    {
        $params = [
            'transaction_details' => [
                'order_id' => $this->pembelian->id,
                'gross_amount' => $this->pembelian->paketUjian->harga,
            ],
            'item_details' => [
                [
                    'id' => $this->pembelian->paketUjian->id,
                    'price' => $this->pembelian->harga,
                    'quantity' => 1,
                    'name' => $this->pembelian->paketUjian->nama,
                ],
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => '081234567890',
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}
