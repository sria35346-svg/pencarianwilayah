<?php

namespace App\Http\Controllers;

use App\Models\DataCrawling;
use App\Models\ImportHistory;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataCrawlingController extends Controller
{
    public function index(Request $request)
    {
        // Membaca daftar provinsi dari file Excel.
        $path = storage_path(
            'app/daftar_kode_dan_nama_provinsi.xlsx'
        );

        $daftarProvinsi = collect();

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();

            // Mengambil nama provinsi dari kolom B.
            for (
                $baris = 2;
                $baris <= $sheet->getHighestRow();
                $baris++
            ) {
                $namaProvinsi = trim(
                    (string) $sheet->getCell('B' . $baris)->getValue()
                );

                if ($namaProvinsi !== '') {
                    $daftarProvinsi->push($namaProvinsi);
                }
            }

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }

        // Menghilangkan duplikasi dan mengurutkan nama provinsi.
        $daftarProvinsi = $daftarProvinsi
            ->unique()
            ->sort()
            ->values();

        // Menghitung seluruh data dalam database.
        $totalData = DataCrawling::count();

        // Menyiapkan query data crawling.
        $query = DataCrawling::query();

        // Memfilter data berdasarkan provinsi yang dipilih.
        if ($request->filled('provinsi')) {
            $query->where('provinsi', $request->input('provinsi'));
        }
        
        $namaKategoriAktif = 'Semua Kategori (Keseluruhan Data)';

        // Memfilter data berdasarkan riwayat impor yang dipilih.
        if ($request->filled('import_id')) {
            $query->where('import_history_id', $request->input('import_id'));
            $history = ImportHistory::find($request->input('import_id'));
            if ($history) {
                $namaKategoriAktif = $history->kategori;
            }
        } elseif (session()->has('namaData')) {
            $namaKategoriAktif = session('namaData');
        }

        // Menghitung jumlah data sesuai filter.
        $totalHasilPencarian = (clone $query)->count();

        // Mengambil data untuk ditampilkan pada tabel.
        $dataCrawling = $query
            ->orderBy('nama_tempat')
            ->paginate(20)
            ->withQueryString();

        return view('data-crawling.index', compact(
            'dataCrawling',
            'totalData',
            'totalHasilPencarian',
            'daftarProvinsi',
            'namaKategoriAktif'
        ));
    }

    public function history()
    {
        $riwayatImpor = ImportHistory::latest()->get();
        return view('data-crawling.history', compact('riwayatImpor'));
    }

    public function destroyHistory($id)
    {
        $history = ImportHistory::findOrFail($id);
        
        // Hapus semua data yang berkaitan dengan riwayat ini
        DataCrawling::where('import_history_id', $history->id)->delete();
        
        // Hapus riwayat impor
        $history->delete();

        return redirect()->back()->with('success', 'Riwayat dan seluruh data terkait berhasil dihapus.');
    }
}