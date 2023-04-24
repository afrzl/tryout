<?php

namespace App\Models;

use App\Models\Soal;
use App\Models\Jawaban;
use App\Models\Pembelian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JawabanPeserta extends Model
{
    use HasFactory;

    protected $table = 'jawaban_peserta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pembelian_id',
        'jawaban_id',
    ];

    /**
     * Get the pembelian that owns the JawabanPeserta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    /**
     * Get the jawaban that owns the JawabanPeserta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jawaban()
    {
        return $this->belongsTo(Jawaban::class);
    }

    /**
     * Get the soal that owns the JawabanPeserta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
