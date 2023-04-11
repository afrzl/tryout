<?php

namespace App\Models;

use App\Models\Soal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jawaban extends Model
{
    use HasFactory;

    protected $table = 'jawaban';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'soal_id',
        'jawaban',
        'isKunci',
    ];

    /**
     * Get the soal that owns the Jawaban
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class);
    }
}
