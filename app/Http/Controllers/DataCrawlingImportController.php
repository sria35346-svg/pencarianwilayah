<?php

namespace App\Http\Controllers;

use App\Models\DataCrawling;
use App\Imports\DataCrawlingImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataCrawlingImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:20480',
        ]);

        $file = $request->file('file');

        // Mengambil nama file tanpa ekstensi.
        $namaData = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        try {
            DB::transaction(function () use ($file) {
                // Menghapus seluruh data lama.
                DataCrawling::query()->delete();

                // Mengimpor data dari file Excel baru.
                Excel::import(
                    new DataCrawlingImport(),
                    $file
                );
            });

            $jumlahData = DataCrawling::count();

            // Menyimpan nama data agar tetap tersedia
            // ketika pengguna melakukan pencarian provinsi.
            session()->put('namaData', $namaData);

            return redirect('/')
                ->with(
                    'success',
                    "Impor berhasil. Total data saat ini: {$jumlahData}."
                );

        } catch (\Throwable $e) {
            report($e);

            return redirect('/')
                ->with(
                    'error',
                    'Impor gagal. Data lama dipertahankan jika transaksi database berhasil dibatalkan.'
                );
        }
    }

    public function export(Request $request)
    {
        // Mengambil provinsi yang dipilih.
        $provinsi = $request->query('provinsi');

        // Mengambil nama data dari file yang diimpor.
        $namaData = session('namaData', 'data_crawling');

        // Mengubah nama provinsi menjadi format nama file.
        if ($provinsi) {
            $namaProvinsi = str_replace(
                ' ',
                '_',
                strtolower($provinsi)
            );

            // Menghilangkan nama provinsi dari akhir nama dataset
            // agar nama provinsi tidak ditulis dua kali.
            $namaDasar = preg_replace(
                '/_' . preg_quote($namaProvinsi, '/') . '$/i',
                '',
                $namaData
            );

            $namaDasar = $namaDasar ?: $namaData;

            $namaFile = $namaDasar . '_' . $namaProvinsi . '.xlsx';

        } else {
            // Export seluruh provinsi.
            $namaDasar = preg_replace(
                '/_(semua_provinsi|[a-z_]+)$/i',
                '',
                $namaData
            );

            $namaDasar = $namaDasar ?: $namaData;

            $namaFile = $namaDasar . '_semua_provinsi.xlsx';
        }

        // Mengunduh data sesuai provinsi yang dipilih.
        return Excel::download(
            new \App\Exports\DataCrawlingExport($provinsi),
            $namaFile
        );
    }
}