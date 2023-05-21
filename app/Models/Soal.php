<?php

namespace App\Models;

use App\Models\Ujian;
use App\Models\Jawaban;
use App\Models\JawabanPeserta;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function jawabanPeserta()
    {
        return $this->hasOne(JawabanPeserta::class, 'soal_id');
    }

    public static function customPaginate($items,$perPage)
    {
        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPage = $currentPage - 1;

        //Create a new Laravel collection from the array data
        $collection = new Collection($items);

        //Define how many items we want to be visible in each page
        $perPage = $perPage;

        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = ($items->count() > $perPage) ? $collection->slice($currentPage * $perPage, $perPage)->all() : $collection->all();

        //Create our paginator and pass it to the view
        $paginatedSearchResults = new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);

    return $paginatedSearchResults;
    }
}
