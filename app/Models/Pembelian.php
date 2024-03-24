<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ujian;
use App\Models\Voucher;
use App\Models\PaketUjian;
use App\Models\JawabanPeserta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'paket_id',
        'user_id',
        'kode_pembelian',
        'batas_pembayaran',
        'nama_kelompok',
    ];

    /**
     * Get the user that owns the Pembelian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the paket that owns the Pembelian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paketUjian()
    {
        return $this->belongsTo(PaketUjian::class, 'paket_id');
    }

    /**
     * Get all of the jawabanPeserta for the Pembelian
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jawabanPeserta()
    {
        return $this->hasMany(JawabanPeserta::class, 'pembelian_id');
    }

    /**
     * Get the voucher that owns the Pembelian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
}
