<?php

namespace App\Models;

use App\Models\Soal;
use App\Traits\Uuids;
use App\Models\Pembelian;
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
        'harga',
        'waktu_pengerjaan',
        'waktu_mulai',
        'waktu_akhir',
    ];

    /**
     * Add a mutator to ensure hashed passwords
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

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
}
