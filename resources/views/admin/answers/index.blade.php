@extends('layouts.admin')

@section('title', 'Kelola Jawaban - HEI Assessment')
@section('page-title', 'Kelola Jawaban')
@section('page-description', 'Data jawaban mahasiswa dari HEI Assessment')

@section('page-actions')
    <!-- <div class="d-flex align-items-center" style="gap: 0.75rem;">
        <div class="filter-group">
            <select id="categoryFilter" class="filter-select" onchange="applyFilters()">
                <option value="">Semua Kategori</option>
                <option value="H" {{ request('category') == 'H' ? 'selected' : '' }}>Harmony</option>
                <option value="E" {{ request('category') == 'E' ? 'selected' : '' }}>Excellence</option>
                <option value="I" {{ request('category') == 'I' ? 'selected' : '' }}>Integrity</option>
            </select>
        </div>
        <button type="button" id="refreshData" class="btn btn-primary btn-sm">🔄 Refresh</button>
    </div> -->
@endsection

@section('content')
<!-- Statistics Overview -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['total_responses'] }}</div>
        <div class="stat-label">Total Jawaban</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['total_users'] }}</div>
        <div class="stat-label">Mahasiswa Aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['total_questions'] }}</div>
        <div class="stat-label">Total Pertanyaan</div>
    </div>
    <!-- <div class="stat-card">
        <div class="stat-number">{{ $stats['avg_completion'] }}%</div>
        <div class="stat-label">Rata-rata Penyelesaian</div>
    </div> -->
</div>

<!-- Filter Section -->
<!-- <div class="card">
    <h3 class="text-h3 mb-4">Filter Data</h3>
    <form method="GET" id="filterForm" class="filter-form">
        <div class="filter-grid">
            <div class="filter-group">
                <label for="category" class="filter-label">Kategori:</label>
                <select name="category" id="category" class="filter-select">
                    <option value="">Semua Kategori</option>
                    <option value="H" {{ request('category') == 'H' ? 'selected' : '' }}>Harmony</option>
                    <option value="E" {{ request('category') == 'E' ? 'selected' : '' }}>Excellence</option>
                    <option value="I" {{ request('category') == 'I' ? 'selected' : '' }}>Integrity</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="date_from" class="filter-label">Dari Tanggal:</label>
                <input type="date" name="date_from" id="date_from" class="filter-input" value="{{ request('date_from') }}">
            </div>
            <div class="filter-group">
                <label for="date_to" class="filter-label">Sampai Tanggal:</label>
                <input type="date" name="date_to" id="date_to" class="filter-input" value="{{ request('date_to') }}">
            </div>
            <div class="filter-group">
                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary btn-sm">🔍 Filter</button>
                    <a href="{{ route('admin.answers.index') }}" class="btn btn-secondary btn-sm">🔄 Reset</a>
                </div>
            </div>
        </div>
    </form>
</div> -->

<!-- Results Table -->
<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-h3">Data Jawaban Mahasiswa</h3>
        <div class="result-count">
            <small style="color: var(--text-muted);">Menampilkan {{ $users->count() }} mahasiswa</small>
        </div>
    </div>

    @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Total Jawaban</th>
                        <th>Avg Score</th>
                        <th>Kategori Dominan</th>
                        <th>Status</th>
                        <th>Terakhir Dijawab</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $userData)
                    @php
                        $user = $userData['user'];
                        $totalAnswers = $userData['total_answers'];
                        $averageScore = $userData['average_score'];
                        $dominantPersonality = $userData['dominant_personality'];
                        $completionStatus = $userData['completion_status'];
                        $lastAnswered = $userData['last_answered'];
                        $categoryAverages = $userData['category_averages'];
                    @endphp
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $user->name }}</strong>
                                <br>
                                <small style="color: var(--text-muted);">{{ $user->email }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $totalAnswers }}</span>
                        </td>
                        <td>
                            <span class="score-badge score-{{ $averageScore >= 4 ? 'high' : ($averageScore >= 3 ? 'medium' : 'low') }}">
                                {{ number_format($averageScore, 1) }}
                            </span>
                        </td>
                        <td>
                            @if($dominantPersonality == 'H')
                                <span class="badge badge-harmony">Harmony</span>
                            @elseif($dominantPersonality == 'E')
                                <span class="badge badge-excellence">Excellence</span>
                            @elseif($dominantPersonality == 'I')
                                <span class="badge badge-integrity">Integrity</span>
                            @else
                                <span class="badge badge-neutral">Belum Ada</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $completionStatus)) }}">
                                {{ $completionStatus }}
                            </span>
                        </td>
                        <td>
                            @if($lastAnswered)
                                <small>{{ \Carbon\Carbon::parse($lastAnswered)->diffForHumans() }}</small>
                                <br>
                                <small style="color: var(--text-muted);">{{ \Carbon\Carbon::parse($lastAnswered)->format('d/m/Y H:i') }}</small>
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center" style="gap: 0.5rem;">
                                <a href="{{ route('admin.answers.show', $user) }}" class="btn btn-primary btn-sm">
                                    👁️ Detail
                                </a>
                                @if($totalAnswers > 0)
                                    <form action="{{ route('admin.answers.destroy.user', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Yakin ingin menghapus semua jawaban {{ $user->name }}?')">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📊</div>
            <h4 class="text-h4">Belum Ada Data Jawaban</h4>
            <p class="text-body">
                @if(request()->hasAny(['category', 'date_from', 'date_to']))
                    Tidak ada data jawaban yang sesuai dengan filter yang dipilih.
                @else
                    Belum ada mahasiswa yang mengerjakan assessment. 
                @endif
            </p>
            @if(request()->hasAny(['category', 'date_from', 'date_to']))
                <a href="{{ route('admin.answers.index') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    🔄 Reset Filter
                </a>
            @endif
        </div>
    @endif
</div>

<!-- Category Distribution -->
@if($users->count() > 0)
<div class="card">
    <h3 class="text-h3 mb-4">Distribusi Kepribadian Dominan</h3>
    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, var(--purple-soft), rgba(177, 156, 217, 0.3));">
            <div class="stat-number" style="color: white;">
                {{ $users->where('dominant_personality', 'H')->count() }}
            </div>
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.8);">Harmony Dominan</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, var(--blue-soft), rgba(168, 200, 236, 0.3));">
            <div class="stat-number" style="color: white;">
                {{ $users->where('dominant_personality', 'E')->count() }}
            </div>
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.8);">Excellence Dominan</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, var(--orange-soft), rgba(255, 169, 129, 0.3));">
            <div class="stat-number" style="color: white;">
                {{ $users->where('dominant_personality', 'I')->count() }}
            </div>
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.8);">Integrity Dominan</div>
        </div>
        <!-- <div class="stat-card">
            <div class="stat-number">
                {{ $users->whereNotIn('dominant_personality', ['H', 'E', 'I'])->count() }}
            </div>
            <div class="stat-label">Belum Lengkap</div>
        </div> -->
    </div>
</div>
@endif
@endsection

@section('styles')
<style>
    .filter-form {
        background: rgba(168, 200, 236, 0.05);
        padding: 1.5rem;
        border-radius: 16px;
        border: 1px solid rgba(168, 200, 236, 0.2);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .filter-select,
    .filter-input {
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        background: var(--bg-card);
        box-shadow: 
            inset 2px 2px 4px var(--shadow-dark),
            inset -2px -2px 4px var(--shadow-light);
        font-family: inherit;
        font-size: 0.875rem;
        color: var(--text-primary);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-select:focus,
    .filter-input:focus {
        outline: none;
        box-shadow: 
            inset 3px 3px 6px var(--shadow-dark),
            inset -3px -3px 6px var(--shadow-light),
            0 0 0 2px var(--blue-soft);
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
        background: var(--orange-soft);
        color: white;
    }

    .badge-neutral {
        background: var(--text-muted);
        color: white;
    }

    .score-badge {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .score-high {
        background: var(--green-soft);
        color: white;
    }

    .score-medium {
        background: var(--yellow-soft);
        color: var(--text-primary);
    }

    .score-low {
        background: var(--red-soft);
        color: white;
    }

    .status-badge {
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .status-complete {
        background: var(--green-soft);
        color: white;
    }

    .status-almost-complete {
        background: var(--blue-soft);
        color: white;
    }

    .status-in-progress {
        background: var(--yellow-soft);
        color: var(--text-primary);
    }

    .status-just-started {
        background: var(--red-soft);
        color: white;
    }

    .status-no-questions {
        background: var(--text-muted);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted);
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
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
        vertical-align: top;
    }

    .table th {
        background: rgba(168, 200, 236, 0.1);
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .d-flex {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh functionality
    const refreshBtn = document.getElementById('refreshData');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            window.location.reload();
        });
    }

    // Filter form auto-submit on change
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }

    // Date validation
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');
    
    if (dateFrom && dateTo) {
        dateFrom.addEventListener('change', function() {
            dateTo.min = this.value;
        });
        
        dateTo.addEventListener('change', function() {
            dateFrom.max = this.value;
        });
    }
});

function applyFilters() {
    const form = document.getElementById('filterForm');
    form.submit();
}
</script>
@endsection