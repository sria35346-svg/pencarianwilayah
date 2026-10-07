<?php

namespace App\Exports;

use App\Models\DataCrawling;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataCrawlingExport implements FromQuery, WithHeadings, WithMapping
{
    protected ?string $provinsi;

    public function __construct(?string $provinsi = null)
    {
        $this->provinsi = $provinsi;
    }

    public function query(): Builder
    {
        $query = DataCrawling::query();

        if ($this->provinsi !== null && $this->provinsi !== '') {
            $query->where('provinsi', $this->provinsi);
        }

        return $query->orderBy('nama_tempat');
    }

    public function headings(): array
    {
        return [
            'Name',
            'Fulladdress',
            'Categories',
            'Phones',
            'Email',
            'Latitude',
            'Longitude',
            'Province',
        ];
    }

    public function map($data): array
    {
        return [
            $data->nama_tempat,
            $data->alamat,
            $data->kategori,
            $data->telepon,
            $data->email,
            $data->latitude,
            $data->longitude,
            $data->provinsi,
        ];
    }
}