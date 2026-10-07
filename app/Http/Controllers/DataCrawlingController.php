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
        // Membaca daftar provinsi menggunakan helper method
        $daftarProvinsi = $this->getDaftarProvinsi();

        // Menyiapkan query data crawling.
        $query = DataCrawling::query();

        // Mengambil riwayat impor yang dipilih dari query param atau session
        if ($request->filled('import_id')) {
            $history = ImportHistory::find($request->input('import_id'));
        } elseif (session()->has('active_import_id')) {
            $history = ImportHistory::find(session('active_import_id'));
        } else {
            $history = null;
        }

        if ($history) {
            $query->where('import_history_id', $history->id);
            $namaKategoriAktif = $history->kategori;
            
            // Total data difokuskan hanya untuk file/kategori ini
            $totalData = DataCrawling::where('import_history_id', $history->id)->count();
        } else {
            $namaKategoriAktif = 'Belum Ada Kategori';
            $totalData = 0;
            $query->whereRaw('1 = 0'); // Jika kosong, pastikan tabel tidak menampilkan data
        }

        // Memfilter data berdasarkan provinsi yang dipilih.
        if ($request->filled('provinsi')) {
            $query->where('provinsi', $request->input('provinsi'));
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

    public function resetView(Request $request)
    {
        $request->session()->forget('active_import_id');
        return redirect('/')->with('success', 'Tampilan telah di-reset dan siap untuk import file baru.');
    }

    public function home(Request $request)
    {
        $daftarProvinsi = $this->getDaftarProvinsi();
        $daftarKategori = ImportHistory::select('id', 'kategori')->orderBy('created_at', 'desc')->get();

        $query = DataCrawling::query();

        if ($request->filled('import_id')) {
            $query->where('import_history_id', $request->input('import_id'));
        }

        if ($request->filled('provinsi')) {
            $query->where('provinsi', $request->input('provinsi'));
        }

        $totalHasilPencarian = (clone $query)->count();
        $totalDataKeseluruhan = DataCrawling::count();

        $dataCrawling = $query
            ->orderBy('nama_tempat')
            ->paginate(20)
            ->withQueryString();

        return view('data-crawling.home', compact(
            'dataCrawling',
            'daftarProvinsi',
            'daftarKategori',
            'totalHasilPencarian',
            'totalDataKeseluruhan'
        ));
    }

    private function getDaftarProvinsi()
    {
        $path = storage_path('app/daftar_kode_dan_nama_provinsi.xlsx');
        $daftarProvinsi = collect();

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();

            for ($baris = 2; $baris <= $sheet->getHighestRow(); $baris++) {
                $namaProvinsi = trim((string) $sheet->getCell('B' . $baris)->getValue());
                if ($namaProvinsi !== '') {
                    $daftarProvinsi->push($namaProvinsi);
                }
            }

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }

        return $daftarProvinsi->unique()->sort()->values();
    }
}