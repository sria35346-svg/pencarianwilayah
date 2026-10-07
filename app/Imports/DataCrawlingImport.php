<?php

namespace App\Imports;

use App\Models\DataCrawling;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataCrawlingImport implements ToModel, WithHeadingRow
{
    private static ?array $daftarProvinsi = null;

    public function model(array $row): Model|array|null
    {
        $alamat = $row['fulladdress'] ?? null;

        $provinsi = $this->cariProvinsi($alamat);

        return new DataCrawling([
            'nama_tempat' => $row['name'] ?? null,
            'kategori' => $row['categories'] ?? null,
            'alamat' => $alamat,
            'provinsi' => $provinsi,
            'kabupaten_kota' => null,
            'telepon' => $row['phones'] ?? null,
            'email' => $row['email'] ?? null,

            'latitude' => $this->perbaikiKoordinat(
                $row['latitude'] ?? null,
                'latitude'
            ),

            'longitude' => $this->perbaikiKoordinat(
                $row['longitude'] ?? null,
                'longitude'
            ),
        ]);
    }

    /**
     * Membaca kode dan nama provinsi dari file Excel.
     */
    private function bacaDaftarProvinsi(): array
    {
        if (self::$daftarProvinsi !== null) {
            return self::$daftarProvinsi;
        }

        $path = storage_path(
            'app/daftar_kode_dan_nama_provinsi.xlsx'
        );

        if (!file_exists($path)) {
            throw new \RuntimeException(
                'File daftar_kode_dan_nama_provinsi.xlsx tidak ditemukan di storage/app.'
            );
        }

        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();

        $daftar = [];

        // Baris pertama merupakan judul kolom.
        for (
            $baris = 2;
            $baris <= $sheet->getHighestRow();
            $baris++
        ) {
            $kode = trim(
                (string) $sheet->getCell('A' . $baris)->getValue()
            );

            $nama = trim(
                (string) $sheet->getCell('B' . $baris)->getValue()
            );

            if ($kode === '' || $nama === '') {
                continue;
            }

            $daftar[] = [
                'kode' => $kode,
                'nama' => $nama,
            ];
        }

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        self::$daftarProvinsi = $daftar;

        return $daftar;
    }

    /**
     * Mencari nama provinsi yang tercantum di alamat.
     */
    private function cariProvinsi(?string $alamat): ?string
    {
        if ($alamat === null || trim($alamat) === '') {
            return null;
        }

        $alamatNormal = $this->normalisasi($alamat);
        $daftarProvinsi = $this->bacaDaftarProvinsi();

        // Nama provinsi terpanjang diperiksa lebih dahulu.
        usort(
            $daftarProvinsi,
            fn ($a, $b) => strlen($b['nama']) <=> strlen($a['nama'])
        );

        foreach ($daftarProvinsi as $provinsi) {
            $namaNormal = $this->normalisasi($provinsi['nama']);

            $pola = '/(?<![A-Z0-9])'
                . preg_quote($namaNormal, '/')
                . '(?![A-Z0-9])/';

            if (preg_match($pola, $alamatNormal)) {
                return $provinsi['nama'];
            }
        }

        return null;
    }

    /**
     * Menyamakan format teks untuk pencocokan.
     */
    private function normalisasi(string $teks): string
    {
        $teks = mb_strtoupper($teks, 'UTF-8');
        $teks = preg_replace('/[^A-Z0-9]+/u', ' ', $teks);

        return trim(preg_replace('/\s+/', ' ', $teks));
    }

    /**
     * Memperbaiki dan memvalidasi koordinat.
     */
    private function perbaikiKoordinat(
        mixed $nilai,
        string $jenis
    ): ?float {
        if ($nilai === null || trim((string) $nilai) === '') {
            return null;
        }

        $angka = (float) $nilai;

        if (abs($angka) > 180) {
            $angka /= 10000000;
        }

        if (
            $jenis === 'latitude'
            && ($angka < -90 || $angka > 90)
        ) {
            return null;
        }

        if (
            $jenis === 'longitude'
            && ($angka < -180 || $angka > 180)
        ) {
            return null;
        }

        return $angka;
    }
}