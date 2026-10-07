<?php

namespace App\Http\Controllers;

use App\Models\DataCrawling;
use App\Models\ImportHistory;
use App\Imports\DataCrawlingImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataCrawlingImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:102400',
        ]);

        $file = $request->file('file');

        // Mengambil nama file dan kategori.
        $originalFileName = $file->getClientOriginalName();
        $namaData = pathinfo($originalFileName, PATHINFO_FILENAME);

        try {
            DB::transaction(function () use ($file, $originalFileName, $namaData) {
                // Membuat record riwayat impor.
                $history = ImportHistory::create([
                    'file_name' => $originalFileName,
                    'kategori' => $namaData,
                    'total_data' => 0,
                ]);

                // Mengimpor data dari file Excel baru.
                Excel::import(
                    new DataCrawlingImport($history->id, $namaData),
                    $file
                );
                
                // Update jumlah data untuk riwayat ini.
                $history->update([
                    'total_data' => DataCrawling::where('import_history_id', $history->id)->count()
                ]);
            });

            $jumlahData = DataCrawling::count();

            // Simpan ID riwayat ini ke session agar tampil di halaman utama Data Crawling
            $request->session()->put('active_import_id', $history->id);

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
        $importId = $request->query('import_id');

        if (!$importId) {
            $latestHistory = ImportHistory::latest()->first();
            if ($latestHistory) {
                $importId = $latestHistory->id;
            }
        }

        $namaData = 'data_crawling';
        if ($importId) {
            $history = ImportHistory::find($importId);
            if ($history) {
                $namaData = $history->kategori;
            }
        }

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
            new \App\Exports\DataCrawlingExport($provinsi, $importId),
            $namaFile
        );
    }
}