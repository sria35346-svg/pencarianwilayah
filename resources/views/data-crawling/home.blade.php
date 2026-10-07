@extends('layouts.app')

@section('title', 'Beranda - Semua Data')

@section('content')
        <div class="header">
            <h1>Semua Data Crawling</h1>
            <p>Sistem pencarian dan pengelompokan seluruh data hasil crawling berdasarkan kategori dan wilayah.</p>
        </div>

        {{-- Statistik data --}}
        <div class="stats">
            <div class="stat-card">
                <h3>Total Seluruh Data</h3>
                <div class="number">
                    {{ number_format($totalDataKeseluruhan, 0, ',', '.') }}
                </div>
            </div>

            <div class="stat-card">
                <h3>Total Hasil Pencarian</h3>
                <div class="number">
                    {{ number_format($totalHasilPencarian, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Form pencarian kategori dan provinsi --}}
        <div class="card">
            <h2>Filter Pencarian Data</h2>

            <form action="{{ url('/home') }}" method="GET" class="filter-form">
                <div class="form-group">
                    <label for="import_id">Pilih Kategori (Berdasarkan File)</label>

                    <select name="import_id" id="import_id">
                        <option value="">Semua Kategori</option>

                        @forelse ($daftarKategori as $kategori)
                            <option
                                value="{{ $kategori->id }}"
                                {{ request('import_id') == $kategori->id ? 'selected' : '' }}
                            >
                                {{ $kategori->kategori }}
                            </option>
                        @empty
                            <option value="" disabled>
                                Belum ada kategori yang tersedia
                            </option>
                        @endforelse
                    </select>
                </div>

                <div class="form-group">
                    <label for="provinsi">Pilih Provinsi</label>

                    <select name="provinsi" id="provinsi">
                        <option value="">Semua Provinsi</option>

                        @forelse ($daftarProvinsi as $provinsi)
                            <option
                                value="{{ $provinsi }}"
                                {{ request('provinsi') == $provinsi ? 'selected' : '' }}
                            >
                                {{ $provinsi }}
                            </option>
                        @empty
                            <option value="" disabled>
                                Daftar provinsi belum tersedia
                            </option>
                        @endforelse
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        Cari Data
                    </button>

                    <a href="{{ url('/home') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        {{-- Daftar data dan tombol download --}}
        <div class="card">

            <div class="result-info">
                <h2>Daftar Data</h2>

                <div class="result-actions">
                    <span class="result-count">
                        Menampilkan {{ $dataCrawling->count() }}
                        dari
                        {{ number_format($totalHasilPencarian, 0, ',', '.') }}
                        data
                    </span>

                    <a
                        href="{{ route('data-crawling.export', request()->only(['provinsi', 'import_id'])) }}"
                        class="btn btn-success"
                    >
                        Download Excel
                    </a>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Categories</th>
                            <th>Fulladdress</th>
                            <th>Province</th>
                            <th>Phones</th>
                            <th>Email</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($dataCrawling as $index => $data)
                            <tr>
                                <td>
                                    {{ $dataCrawling->firstItem() + $index }}
                                </td>

                                <td>
                                    {{ $data->nama_tempat ?? '-' }}
                                </td>

                                <td>
                                    {{ $data->kategori ?? '-' }}
                                </td>

                                <td>
                                    {{ $data->alamat ?? '-' }}
                                </td>

                                <td>
                                    {{ $data->provinsi ?? '-' }}
                                </td>

                                <td>
                                    {{ $data->telepon ?? '-' }}
                                </td>

                                <td>
                                    {{ $data->email ?? '-' }}
                                </td>

                                <td>
                                    {{ $data->latitude ?? '-' }}
                                </td>

                                <td>
                                    {{ $data->longitude ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <i class="ph ph-folder-open"></i>
                                        <span>Belum ada data yang tersedia.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Navigasi halaman --}}
            <div class="pagination-wrapper">
                {{ $dataCrawling->links() }}
            </div>

        </div>
@endsection
