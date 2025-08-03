<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Import CSV - Dashboard Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #e6e7ee;
            min-height: 100vh;
            padding: 2rem;
        }

        :root {
            --bg-primary: #e6e7ee;
            --bg-card: #e6e7ee;
            
            --purple-soft: #b19cd9;
            --green-soft: #a8d5ba;
            --blue-soft: #a8c8ec;
            --yellow-soft: #f7e98e;
            --red-soft: #e5a3a3;
            
            --shadow-dark: #d1d2d9;
            --shadow-light: #fbfcff;
            
            --text-primary: #3e4152;
            --text-secondary: #6c7293;
            --text-muted: #a7a9b8;
        }

        .text-h1 { font-size: 2.5rem; font-weight: 700; line-height: 1.2; color: var(--text-primary); }
        .text-h2 { font-size: 2rem; font-weight: 600; line-height: 1.3; color: var(--text-primary); }
        .text-h3 { font-size: 1.5rem; font-weight: 500; line-height: 1.4; color: var(--text-primary); }
        .text-h4 { font-size: 1.25rem; font-weight: 500; line-height: 1.5; color: var(--text-primary); }
        .text-body { font-size: 1rem; font-weight: 400; line-height: 1.6; color: var(--text-secondary); }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section {
            background: var(--bg-card);
            padding: 2.5rem;
            margin-bottom: 2rem;
            border-radius: 25px;
            box-shadow: 
                12px 12px 24px var(--shadow-dark),
                -12px -12px 24px var(--shadow-light);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .card {
            background: var(--bg-card);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 
                10px 10px 20px var(--shadow-dark),
                -10px -10px 20px var(--shadow-light);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin: 1.5rem 0;
        }

        .btn {
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 
                6px 6px 12px var(--shadow-dark),
                -6px -6px 12px var(--shadow-light);
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-family: inherit;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 
                8px 8px 16px var(--shadow-dark),
                -8px -8px 16px var(--shadow-light);
        }

        .btn-success {
            background: var(--green-soft);
            color: white;
        }

        .btn-secondary {
            background: var(--text-muted);
            color: white;
        }

        .btn-primary {
            background: var(--blue-soft);
            color: white;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .me-2 {
            margin-right: 0.5rem;
        }

        .table {
            width: 100%;
            background: var(--bg-card);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 
                inset 4px 4px 8px var(--shadow-dark),
                inset -4px -4px 8px var(--shadow-light);
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(209, 210, 217, 0.3);
        }

        .table th {
            background: rgba(168, 200, 236, 0.1);
            font-weight: 600;
            color: var(--text-primary);
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .alert {
            padding: 1rem 1.5rem;
            margin: 1rem 0;
            border-radius: 12px;
            font-weight: 500;
        }

        .alert-success {
            background: var(--green-soft);
            color: white;
        }

        .alert-danger {
            background: var(--red-soft);
            color: white;
        }

        .alert-warning {
            background: var(--yellow-soft);
            color: var(--text-primary);
        }

        .badge {
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-harmony {
            background: var(--purple-soft);
            color: white;
        }

        .badge-excellence {
            background: var(--blue-soft);
            color: white;
        }

        .badge-integrity {
            background: var(--green-soft);
            color: white;
        }

        .badge-active {
            background: var(--green-soft);
            color: white;
        }

        .badge-inactive {
            background: var(--red-soft);
            color: white;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .stat-item {
            background: var(--bg-card);
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 
                6px 6px 12px var(--shadow-dark),
                -6px -6px 12px var(--shadow-light);
            text-align: center;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .text-h1 { font-size: 2rem; }
            body { padding: 1rem; }
            .d-flex {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-h1">Preview Import CSV</h1>
                    <p class="text-body">Review data sebelum diimpor ke database</p>
                </div>
                <div>
                    <a href="{{ route('dashboard.import.form') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card">
            <h3 class="text-h3" style="margin-bottom: 1rem;">Ringkasan Import</h3>
            <div class="summary-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ count($previewData) }}</div>
                    <div class="stat-label">Total Data Valid</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ count($errors) }}</div>
                    <div class="stat-label">Error Ditemukan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ collect($previewData)->where('category', 'H')->count() }}</div>
                    <div class="stat-label">Harmony</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ collect($previewData)->where('category', 'E')->count() }}</div>
                    <div class="stat-label">Excellence</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ collect($previewData)->where('category', 'I')->count() }}</div>
                    <div class="stat-label">Integrity</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ collect($previewData)->where('is_active', true)->count() }}</div>
                    <div class="stat-label">Aktif</div>
                </div>
            </div>
        </div>

        <!-- Errors (jika ada) -->
        @if(!empty($errors))
        <div class="card">
            <h3 class="text-h3" style="margin-bottom: 1rem; color: var(--red-soft);">⚠️ Error Ditemukan</h3>
            <div class="alert alert-danger">
                <strong>Perbaiki error berikut sebelum melanjutkan import:</strong>
                <ul style="margin: 0.5rem 0 0 1.5rem;">
                    @foreach($errors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="{{ route('dashboard.import.form') }}" class="btn btn-secondary">Ganti File CSV</a>
            </div>
        </div>
        @endif

        <!-- Preview Data -->
        @if(!empty($previewData))
        <div class="card">
            <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 1.5rem;">
                <h3 class="text-h3">Preview Data</h3>
                @if(empty($errors))
                    <form action="{{ route('dashboard.import.confirm') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Konfirmasi Import</button>
                    </form>
                @endif
            </div>

            @if(empty($errors))
                <div class="alert alert-success">
                    ✅ Semua data valid dan siap diimpor!
                </div>
            @endif

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Baris</th>
                            <th>Urutan</th>
                            <th>Pertanyaan</th>
                            <th>Kategori</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previewData as $data)
                        <tr>
                            <td>
                                <span class="badge badge-primary">{{ $data['line'] }}</span>
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $data['order'] }}</span>
                            </td>
                            <td style="max-width: 400px;">
                                {{ Str::limit($data['question_text'], 80) }}
                            </td>
                            <td>
                                @if($data['category'] == 'H')
                                    <span class="badge badge-harmony">Harmony</span>
                                @elseif($data['category'] == 'E')
                                    <span class="badge badge-excellence">Excellence</span>
                                @else
                                    <span class="badge badge-integrity">Integrity</span>
                                @endif
                            </td>
                            <td>
                                @if($data['is_active'])
                                    <span class="badge badge-active">Aktif</span>
                                @else
                                    <span class="badge badge-inactive">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</body>
</html>