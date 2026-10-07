@extends('layouts.app')

@section('title', 'Data Hasil Crawling')

@section('content')
        <div class="header">
            <h1>Data Hasil Crawling</h1>
            <p>Sistem pencarian dan pengelompokan data hasil crawling berdasarkan wilayah.</p>
        </div>

        {{-- Notifikasi berhasil --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <h2>Informasi Kategori</h2>
            <p>
                <strong>Nama Kategori Aktif:</strong>
                {{ $namaKategoriAktif }}
            </p>
        </div>

        {{-- Notifikasi kesalahan --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form import Excel --}}
        <div class="card">
            <h2>Import Data Excel</h2>

            <form action="{{ route('data-crawling.import') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="form-group">
                    <label for="file">Pilih File Excel</label>
                    <input
                        type="file"
                        name="file"
                        id="file"
                        accept=".xlsx,.xls,.csv"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    Import Data
                </button>
            </form>
        </div>

        {{-- Statistik data --}}
        <div class="stats">
            <div class="stat-card">
                <h3>Total Seluruh Data</h3>
                <div class="number">
                    {{ number_format($totalData, 0, ',', '.') }}
                </div>
            </div>

            <div class="stat-card">
                <h3>Total Hasil Pencarian</h3>
                <div class="number">
                    {{ number_format($totalHasilPencarian, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Form pencarian provinsi --}}
        <div class="card">
            <h2>Pencarian Berdasarkan Wilayah</h2>

            <form action="{{ url('/') }}" method="GET" class="filter-form">
                @if(request('import_id'))
                    <input type="hidden" name="import_id" value="{{ request('import_id') }}">
                @endif
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

                    <a href="{{ url('/') }}" class="btn btn-secondary">
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