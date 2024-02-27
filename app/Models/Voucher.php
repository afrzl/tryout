<?php

namespace App\Models;

use App\Models\User;
use App\Models\Pembelian;
use App\Models\PaketUjian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'voucher';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode',
        'diskon',
        'himada_id',
        'kuota',
        'paket_ujian_id',
    ];

    /**
     * Get the user that owns the Voucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the pembelian for the Voucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'voucher_id', 'id');
    }

    /**
     * Get the paketUjian that owns the Voucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paketUjian()
    {
        return $this->belongsTo(PaketUjian::class);
    }
}
