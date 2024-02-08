<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ujian;
use App\Traits\Uuids;
use App\Models\JawabanPeserta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UjianUser extends Model
{
    use HasFactory, Uuids;
    protected $table = 'ujian_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ujian_id',
        'user_id',
        'status',
        'jml_benar',
        'jml_salah',
        'jml_kosong',
        'nilai',
        'nilai_twk',
        'nilai_tiu',
        'nilai_tkp',
        'waktu_mulai',
        'waktu_akhir',
        'is_first',
    ];

    /**
     * Get the user that owns the UjianUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the ujian that owns the UjianUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    /**
     * Get all of the jawabanPeserta for the UjianUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jawabanPeserta()
    {
        return $this->hasMany(JawabanPeserta::class, 'ujian_user_id', 'id');
    }
}
