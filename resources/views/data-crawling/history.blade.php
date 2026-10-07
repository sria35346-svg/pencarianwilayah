@extends('layouts.app')

@section('title', 'Riwayat Impor')

@section('content')
        <div class="header">
            <h1>Riwayat Impor Data</h1>
            <p>Daftar file Excel yang telah diimpor ke dalam sistem.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="ph-fill ph-check-circle" style="font-size: 20px;"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <h2>Riwayat Impor</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal & Waktu</th>
                            <th>Nama File</th>
                            <th>Kategori</th>
                            <th>Jumlah Data</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatImpor as $riwayat)
                            <tr>
                                <td>{{ $riwayat->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $riwayat->file_name }}</td>
                                <td>{{ $riwayat->kategori }}</td>
                                <td>{{ number_format($riwayat->total_data, 0, ',', '.') }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="{{ url('/?import_id=' . $riwayat->id) }}" class="btn-action btn-action-primary" title="Lihat Detail">
                                            <i class="ph ph-eye"></i>
                                        </a>
                                        <a href="{{ route('data-crawling.export', ['import_id' => $riwayat->id]) }}" class="btn-action btn-action-success" title="Download Excel">
                                            <i class="ph ph-download-simple"></i>
                                        </a>
                                        <form action="{{ route('data-crawling.destroy-history', $riwayat->id) }}" method="POST" onsubmit="return confirm('Peringatan: Anda akan menghapus riwayat beserta seluruh data yang ada di dalamnya secara permanen. Apakah Anda yakin?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-danger" title="Hapus Riwayat & Data">
                                                <i class="ph ph-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="ph ph-clock-counter-clockwise"></i>
                                        <span>Belum ada riwayat impor.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
@endsection
