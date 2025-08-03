questions/preview.blade.php

@extends('layouts.admin')

@section('title', 'Preview Import CSV - HEI Assessment')
@section('page-title', 'Preview Import CSV')
@section('page-description', 'Review data sebelum diimpor ke database')

@section('page-actions')
    <a href="{{ route('admin.questions.import.form') }}" class="btn btn-secondary">
        ← Kembali ke Upload
    </a>
@endsection

@section('content')
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
        <a href="{{ route('admin.questions.import.form') }}" class="btn btn-warning">📝 Ganti File CSV</a>
    </div>
</div>
@endif

<!-- Preview Data -->
@if(!empty($previewData))
<div class="card">
    <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 1.5rem;">
        <h3 class="text-h3">Preview Data</h3>
        @if(empty($errors))
            <form action="{{ route('admin.questions.import.store') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">✅ Konfirmasi Import</button>
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
@endsection

@section('styles')
<style>
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

    .badge {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-primary {
        background: var(--blue-soft);
        color: white;
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
        background: #ffa981;
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

    .table-responsive {
        overflow-x: auto;
    }

    @media (max-width: 768px) {
        .summary-stats {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmation before import
    const importForm = document.querySelector('form[action*="import.store"]');
    if (importForm) {
        importForm.addEventListener('submit', function(e) {
            const totalData = {{ count($previewData) }};
            if (!confirm(`Yakin ingin mengimpor ${totalData} pertanyaan ke database?`)) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>
@endsection