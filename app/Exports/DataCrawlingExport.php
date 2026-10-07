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
    protected ?int $importId;

    public function __construct(?string $provinsi = null, ?int $importId = null)
    {
        $this->provinsi = $provinsi;
        $this->importId = $importId;
    }

    public function query(): Builder
    {
        $query = DataCrawling::query();

        if ($this->provinsi !== null && $this->provinsi !== '') {
            $query->where('provinsi', $this->provinsi);
        }
        
        if ($this->importId !== null) {
            $query->where('import_history_id', $this->importId);
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