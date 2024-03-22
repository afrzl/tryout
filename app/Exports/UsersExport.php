<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromQuery, WithMapping, WithHeadings
{
    public function query()
    {
        return User::query()
                    ->with('roles', 'usersDetail')
                    ->doesntHave('roles')
                    ->orderBy('created_at');
    }

    function getWilayahName($id) {
        return Wilayah::find($id);
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->email,
            $user->name,
            $user->usersDetail->no_hp ?? '',
            isset($user->usersDetail->provinsi) ? $this->getWilayahName($user->usersDetail->provinsi)->nama : '',
            isset($user->usersDetail->kabupaten) ? $this->getWilayahName($user->usersDetail->kabupaten)->nama : '',
            isset($user->usersDetail->kecamatan) ? $this->getWilayahName($user->usersDetail->kecamatan)->nama : '',
            $user->usersDetail->asal_sekolah ?? '',
            isset($user->usersDetail->prodi) ? ($user->usersDetail->prodi == 1 ? 'DIII - Statistika' : ($user->usersDetail->prodi == 2 ? 'DIV - Statistika' : 'DIV - Komputasi Statistik')) : '',
            isset($user->usersDetail->penempatan) ? $this->getWilayahName($user->usersDetail->penempatan)->nama : '',
            $user->usersDetail->instagram ?? '',
            $user->profile_photo_url,
            $user->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Email',
            'Nama',
            'No. HP',
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Asal Sekolah',
            'Prodi',
            'Penempatan',
            'Instagram',
            'Link Foto Profil',
            'Tanggal Pembuatan Akun',
        ];
    }
}
