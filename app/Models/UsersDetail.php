<?php

namespace App\Models;

use App\Models\User;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsersDetail extends Model
{
    use HasFactory, Uuids;

    protected $table = 'users_detail';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'no_hp',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'asal_sekolah',
        'sumber_informasi',
        'prodi',
        'penempatan',
        'instagram',
    ];

    protected $casts = [
        'sumber_informasi' => 'array'
    ];

    /**
     * Get the user associated with the UsersDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id');
    }
}
