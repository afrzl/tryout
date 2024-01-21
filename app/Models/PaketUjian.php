<?php

namespace App\Models;

use App\Models\Ujian;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaketUjian extends Model
{
    use HasFactory, Uuids;

    protected $table = 'paket_ujian';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'waktu_mulai',
        'waktu_akhir',
    ];

    /**
     * The ujian that belong to the PaketUjian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ujian()
    {
        return $this->belongsToMany(Ujian::class);
    }

}
