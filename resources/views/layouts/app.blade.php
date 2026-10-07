<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Data Hasil Crawling')</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --bg-color: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --card-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.05), 0 4px 6px -4px rgb(0 0 0 / 0.05);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-color);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: white;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(0,0,0,0.06);
            flex-shrink: 0;
            z-index: 50;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
        }
        
        .sidebar-brand {
            padding: 24px;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .sidebar-brand a {
            color: white;
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        
        .sidebar-nav a i {
            font-size: 20px;
        }

        .sidebar-nav a:hover {
            color: white;
            background: var(--sidebar-hover);
            transform: translateX(4px);
        }

        .sidebar-nav a.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        /* Main Layout */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            width: calc(100% - 260px);
            margin-left: 260px;
        }

        .main-content {
            padding: 40px;
            flex: 1;
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
        }

        /* Headers */
        .header {
            margin-bottom: 32px;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 8px;
            letter-spacing: -1px;
        }

        .header p {
            color: var(--text-muted);
            font-size: 15px;
        }

        /* Cards */
        .card {
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.05), 0 8px 10px -6px rgb(0 0 0 / 0.05);
        }

        .card h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 24px;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #475569;
        }

        input[type="file"],
        select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: #f8fafc;
            font-size: 14px;
            font-family: inherit;
            color: var(--text-main);
            transition: all 0.2s;
        }

        input[type="file"]::file-selector-button {
            background: #e2e8f0;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-family: inherit;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            margin-right: 12px;
            transition: all 0.2s;
        }

        input[type="file"]::file-selector-button:hover {
            background: #cbd5e1;
        }

        input[type="file"]:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: white;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.35);
        }

        .btn-success {
            background: #10b981;
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-success:hover {
            background: #059669;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            color: white;
            text-decoration: none;
        }

        .btn-action i {
            font-size: 18px;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-action:active {
            transform: translateY(0);
        }

        .btn-action-primary {
            background: var(--primary);
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }

        .btn-action-primary:hover {
            background: var(--primary-hover);
            box-shadow: 0 6px 8px rgba(59, 130, 246, 0.3);
        }

        .btn-action-success {
            background: #10b981;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
        }

        .btn-action-success:hover {
            background: #059669;
            box-shadow: 0 6px 8px rgba(16, 185, 129, 0.3);
        }

        .btn-action-danger {
            background: #ef4444;
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
        }

        .btn-action-danger:hover {
            background: #dc2626;
            box-shadow: 0 6px 8px rgba(239, 68, 68, 0.3);
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-danger ul {
            padding-left: 24px;
            margin-top: 8px;
        }

        /* Stats Section */
        .stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px 32px;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: var(--primary);
            border-radius: 6px 0 0 6px;
        }

        .stat-card h3 {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -1px;
        }

        /* Filters */
        .filter-form {
            display: flex;
            align-items: flex-end;
            gap: 16px;
            flex-wrap: wrap;
        }

        .filter-form .form-group {
            flex: 1;
            min-width: 240px;
            margin-bottom: 0;
        }

        .filter-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Results Info */
        .result-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .result-info h2 {
            margin-bottom: 0;
        }

        .result-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .result-count {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
            background: #f1f5f9;
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
        }

        /* Table */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }

        thead {
            background: #f8fafc;
            border-bottom: 2px solid var(--border-color);
        }

        th, td {
            padding: 16px 20px;
            text-align: left;
            font-size: 14px;
        }

        th {
            color: #475569;
            font-weight: 600;
            white-space: nowrap;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        td {
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        tbody tr {
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f8fafc;
        }
        
        tbody tr:last-child td {
            border-bottom: none;
        }

        .empty-state {
            text-align: center;
            padding: 64px 20px;
            color: var(--text-muted);
            font-size: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
        
        .empty-state i {
            font-size: 48px;
            color: #cbd5e1;
        }

        /* Pagination */
        .pagination-wrapper {
            margin-top: 32px;
        }

        .pagination-wrapper nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .pagination-wrapper nav p {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .pagination-wrapper nav div:last-child {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pagination-wrapper nav a,
        .pagination-wrapper nav span[aria-current="page"] span,
        .pagination-wrapper nav span[aria-disabled="true"] span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            background: white;
            color: #475569;
            transition: all 0.2s;
        }
        
        .pagination-wrapper nav a:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: #f0fdf4; /* Very subtle tint */
        }

        .pagination-wrapper nav span[aria-current="page"] span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }

        /* Responsive Media Queries */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 0;
                height: auto;
                position: sticky;
                top: 0;
            }

            .sidebar-brand {
                border-bottom: none;
                padding: 16px 20px;
            }

            .sidebar-nav {
                flex-direction: row;
                padding: 0 12px;
                gap: 8px;
            }

            .sidebar-nav a {
                padding: 12px;
                border-radius: 8px;
            }
            
            .sidebar-nav a span {
                display: none;
            }

            .sidebar-nav a:hover,
            .sidebar-nav a.active {
                background: rgba(255,255,255,0.1);
                transform: none;
                box-shadow: none;
            }

            .main-wrapper {
                width: 100%;
                margin-left: 0;
            }

            .main-content {
                padding: 24px 16px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-actions {
                width: 100%;
            }

            .filter-actions .btn {
                flex: 1;
            }

            .result-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .result-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="ph-fill ph-map-pin" style="font-size: 28px; color: var(--primary);"></i>
            <a href="{{ url('/') }}">Pencarian Wilayah</a>
        </div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                    <i class="ph ph-database"></i>
                    <span>Data Crawling</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/history') }}" class="{{ request()->is('history') ? 'active' : '' }}">
                    <i class="ph ph-clock-counter-clockwise"></i>
                    <span>Riwayat Impor</span>
                </a>
            </li>
        </ul>
    </aside>
    
    <div class="main-wrapper">
        <main class="main-content">
            @yield('content')
        </main>
    </div>
</body>
</html>
