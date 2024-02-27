<?php

namespace App\Models;

use App\Models\Soal;
use App\Models\User;
use App\Traits\Uuids;
use App\Models\Pembelian;
use App\Models\UjianUser;
use App\Models\PaketUjian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ujian extends Model
{
    use HasFactory, Uuids;

    protected $table = 'ujian';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $fillable = [
        'nama',
        'deskripsi',
        'peraturan',
        'jenis_ujian',
        'lama_pengerjaan',
        'waktu_mulai',
        'waktu_akhir',
        'waktu_pengumuman',
        'isPublished',
        'tipe_ujian',
        'tampil_kunci',
        'tampil_nilai',
        'tampil_poin',
        'random',
        'random_pilihan',
        'jumlah_soal'
    ];

    /**
     * Get all of the soal for the Ujian
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function soal()
    {
        return $this->hasMany(Soal::class, 'ujian_id', 'id');
    }

    /**
     * Get all of the pembelian for the Ujian
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'ujian_id');
    }

    /**
     * The paketUjian that belong to the Ujian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function paketUjian()
    {
        return $this->belongsToMany(PaketUjian::class);
    }

    /**
     * Get all of the ujianUser for the Ujian
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ujianUser()
    {
        return $this->hasMany(UjianUser::class, 'ujian_id', 'id');
    }
}
