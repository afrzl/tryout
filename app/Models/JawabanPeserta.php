<?php

namespace App\Models;

use App\Models\Soal;
use App\Models\Jawaban;
use App\Models\Pembelian;
use App\Models\UjianUser;
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
        'ujian_user_id',
        'soal_id',
        'jawaban_id',
        'ragu_ragu',
        'poin',
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

    /**
     * Get the ujianUser that owns the JawabanPeserta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ujianUser()
    {
        return $this->belongsTo(UjianUser::class);
    }
}
