<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataCrawling extends Model
{
    protected $table = 'data_crawling';

    protected $fillable = [
        'import_history_id',
        'nama_data',
        'nama_tempat',
        'kategori',
        'alamat',
        'provinsi',
        'kabupaten_kota',
        'telepon',
        'email',
        'latitude',
        'longitude',
    ];
}