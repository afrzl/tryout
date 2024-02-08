<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected $pembelian;

    public function __construct($pembelian, $metode, $item_details, $total)
    {
        parent::__construct();

        $this->pembelian = $pembelian;
        $this->metode = $metode;
        $this->item_details = $item_details;
        $this->total = $total;
    }

    public function getSnapToken()
    {
        $params = [
            'transaction_details' => [
                'order_id' => $this->pembelian->id . "-" . $this->metode . rand(10, 100),
                'gross_amount' => $this->total,
            ],
            'item_details' => $this->item_details,
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->usersDetail->no_hp,
            ]
        ];
        // return dd($params);

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}
