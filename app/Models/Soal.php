<?php

namespace App\Models;

use App\Models\Ujian;
use App\Models\Jawaban;
use App\Models\JawabanPeserta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $fillable = [
        'ujian_id',
        'soal',
    ];

    /**
     * Get the ujian that owns the Soal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    /**
     * Get all of the jawaban for the Soal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'soal_id', 'id');
    }

    /**
     * Get all of the jawabanPeserta for the Soal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jawabanPeserta()
    {
        return $this->hasMany(JawabanPeserta::class, 'soal_id');
    }
}
