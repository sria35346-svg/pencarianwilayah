<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Hasil Crawling</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #333;
            padding: 30px;
        }

        .container {
            max-width: 1400px;
            margin: auto;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            margin-bottom: 25px;
        }

        .card h2 {
            font-size: 19px;
            margin-bottom: 20px;
            color: #1f2937;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="file"],
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-success:hover {
            background: #15803d;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-danger ul {
            padding-left: 20px;
            margin-top: 8px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border-left: 5px solid #2563eb;
        }

        .stat-card h3 {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .stat-card .number {
            font-size: 27px;
            font-weight: bold;
            color: #1f2937;
        }

        .filter-form {
            display: flex;
            align-items: flex-end;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-form .form-group {
            flex: 1;
            min-width: 220px;
            margin-bottom: 0;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .result-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .result-info h2 {
            margin-bottom: 0;
        }

        .result-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .result-count {
            color: #6b7280;
            font-size: 14px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }

        thead {
            background: #f3f4f6;
        }

        th,
        td {
            padding: 13px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            vertical-align: top;
        }

        th {
            color: #374151;
            font-weight: bold;
            white-space: nowrap;
        }

        td {
            color: #4b5563;
            line-height: 1.5;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .empty-state {
            text-align: center;
            padding: 35px;
            color: #6b7280;
        }

        .pagination-wrapper {
            margin-top: 20px;
        }

        .pagination-wrapper nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .pagination-wrapper nav p {
            font-size: 13px;
            color: #6b7280;
        }

        .pagination-wrapper nav div:last-child {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .pagination-wrapper nav a,
        .pagination-wrapper nav span[aria-current="page"] span,
        .pagination-wrapper nav span[aria-disabled="true"] span {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            background: white;
            color: #374151;
        }

        .pagination-wrapper nav span[aria-current="page"] span {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-form .form-group {
                width: 100%;
            }

            .filter-actions {
                width: 100%;
            }

            .filter-actions .btn {
                flex: 1;
                text-align: center;
            }

            .result-info {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container">

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

        {{-- Nama data yang berhasil diimpor --}}
        @if (session('namaData'))
            <div class="card">
                <h2>Informasi Data</h2>
                <p>
                    <strong>Nama Data:</strong>
                    {{ session('namaData') }}
                </p>
            </div>
        @endif

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
                        href="{{ route('data-crawling.export', request()->only('provinsi')) }}"
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
                                <td colspan="9" class="empty-state">
                                    Belum ada data yang tersedia.
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

    </div>
</body>
</html>