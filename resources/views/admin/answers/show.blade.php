@extends('layouts.admin')

@section('title', 'Detail Jawaban - HEI Assessment')
@section('page-title', 'Detail Jawaban: ' . $user->name)
@section('page-description', 'Analisis jawaban mahasiswa untuk HEI Assessment')

@section('page-actions')
    <div class="d-flex align-items-center" style="gap: 0.75rem;">
        @if($attempts->count() > 1)
            <div class="filter-group">
                <select onchange="changeAttempt(this.value)" class="filter-select">
                    @foreach($attempts as $attemptNum)
                        <option value="{{ $attemptNum }}" {{ $attempt == $attemptNum ? 'selected' : '' }}>
                            Percobaan ke-{{ $attemptNum }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        <a href="{{ route('admin.answers.index') }}" class="btn btn-secondary">
            ← Kembali ke Daftar
        </a>
    </div>
@endsection

@section('content')
<!-- Student Information -->
<div class="card">
    <h3 class="text-h3 mb-4">Informasi Mahasiswa</h3>
    <div class="student-info">
        <div class="info-grid">
            <div class="info-item">
                <strong>Nama:</strong> {{ $user->name }}
            </div>
            <div class="info-item">
                <strong>Email:</strong> {{ $user->email }}
            </div>
            <div class="info-item">
                <strong>Username:</strong> {{ $user->username }}
            </div>
            <div class="info-item">
                <strong>Percobaan:</strong> {{ $attempt }} dari {{ $attempts->count() }}
            </div>
        </div>
    </div>
</div>

<!-- Statistics Overview -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['total_answers'] }}</div>
        <div class="stat-label">Total Jawaban</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['completion_percentage'] }}%</div>
        <div class="stat-label">Penyelesaian</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ number_format($stats['category_stats']['H']['average'], 1) }}</div>
        <div class="stat-label">Avg Harmony</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ number_format($stats['category_stats']['E']['average'], 1) }}</div>
        <div class="stat-label">Avg Excellence</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ number_format($stats['category_stats']['I']['average'], 1) }}</div>
        <div class="stat-label">Avg Integrity</div>
    </div>
</div>

<!-- Category Analysis -->
<div class="card">
    <h3 class="text-h3 mb-4">Analisis per Kategori</h3>
    <div class="category-analysis">
        @foreach(['H' => 'Harmony', 'E' => 'Excellence', 'I' => 'Integrity'] as $category => $categoryName)
        @php
            $categoryData = $stats['category_stats'][$category];
            $average = $categoryData['average'];
            $count = $categoryData['count'];
        @endphp
        <div class="category-card category-{{ strtolower($category) }}">
            <div class="category-header">
                <h4>{{ $categoryName }}</h4>
                <span class="category-badge">{{ $count }} pertanyaan</span>
            </div>
            <div class="category-score">
                <div class="score-display">{{ number_format($average, 1) }}</div>
                <div class="score-bar">
                    <div class="score-fill" style="width: {{ ($average / 5) * 100 }}%"></div>
                </div>
                <div class="score-label">
                    @if($average >= 4)
                        Sangat Tinggi
                    @elseif($average >= 3.5)
                        Tinggi
                    @elseif($average >= 2.5)
                        Sedang
                    @elseif($average >= 2)
                        Rendah
                    @else
                        Sangat Rendah
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Answer Distribution -->
<div class="card">
    <h3 class="text-h3 mb-4">Distribusi Jawaban</h3>
    <div class="distribution-grid">
        @for($i = 1; $i <= 5; $i++)
        @php
            $count = $stats['answer_distribution'][$i] ?? 0;
            $percentage = $stats['total_answers'] > 0 ? ($count / $stats['total_answers']) * 100 : 0;
        @endphp
        <div class="distribution-item">
            <div class="distribution-label">
                {{ $i }} - 
                @if($i == 1) Sangat Tidak Setuju
                @elseif($i == 2) Tidak Setuju
                @elseif($i == 3) Netral
                @elseif($i == 4) Setuju
                @else Sangat Setuju
                @endif
            </div>
            <div class="distribution-bar">
                <div class="distribution-fill" style="width: {{ $percentage }}%"></div>
            </div>
            <div class="distribution-count">{{ $count }} ({{ number_format($percentage, 1) }}%)</div>
        </div>
        @endfor
    </div>
</div>

<!-- Detailed Answers -->
<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-h3">Jawaban Detail</h3>
        <!-- @if($answers->count() > 0)
            <form action="{{ route('admin.answers.destroy.user', $user) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <input type="hidden" name="attempt" value="{{ $attempt }}">
                <button type="submit" class="btn btn-danger btn-sm" 
                        onclick="return confirm('Yakin ingin menghapus semua jawaban percobaan ke-{{ $attempt }}?')">
                    🗑️ Hapus Percobaan Ini
                </button>
            </form>
        @endif -->
    </div>

    @if($answers->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Pertanyaan</th>
                        <th>Kategori</th>
                        <th>Jawaban</th>
                        <th>Waktu</th>
                        <!-- <th>Aksi</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($answers as $index => $answer)
                    <tr>
                        <td>
                            <span class="badge badge-primary">{{ $answer->question->order }}</span>
                        </td>
                        <td style="max-width: 400px;">
                            {{ Str::limit($answer->question->question_text, 80) }}
                        </td>
                        <td>
                            @if($answer->question->category == 'H')
                                <span class="badge badge-harmony">Harmony</span>
                            @elseif($answer->question->category == 'E')
                                <span class="badge badge-excellence">Excellence</span>
                            @else
                                <span class="badge badge-integrity">Integrity</span>
                            @endif
                        </td>
                        <td>
                            <div class="answer-display">
                                <span class="answer-value answer-{{ $answer->answer_value }}">
                                    {{ $answer->answer_value }}
                                </span>
                                <small class="answer-label">{{ $answer->answer_label }}</small>
                            </div>
                        </td>
                        <td>
                            <small>{{ $answer->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <!-- <td>
                            <form action="{{ route('admin.answers.destroy', $answer) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Yakin ingin menghapus jawaban ini?')">
                                    🗑️
                                </button>
                            </form>
                        </td> -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📝</div>
            <h4 class="text-h4">Belum Ada Jawaban</h4>
            <p class="text-body">Mahasiswa belum mengerjakan assessment pada percobaan ke-{{ $attempt }}</p>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .student-info {
        background: rgba(168, 200, 236, 0.1);
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid rgba(168, 200, 236, 0.3);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-item {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .category-analysis {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .category-card {
        padding: 1.5rem;
        border-radius: 16px;
        background: var(--bg-card);
        box-shadow: 
            8px 8px 16px var(--shadow-dark),
            -8px -8px 16px var(--shadow-light);
    }

    .category-h {
        border-left: 4px solid var(--purple-soft);
    }

    .category-e {
        border-left: 4px solid var(--blue-soft);
    }

    .category-i {
        border-left: 4px solid var(--orange-soft);
    }

    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .category-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 6px;
        color: var(--text-muted);
    }

    .score-display {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        text-align: center;
        margin-bottom: 0.5rem;
    }

    .score-bar {
        height: 8px;
        background: var(--shadow-dark);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .score-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--red-soft), var(--yellow-soft), var(--green-soft));
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .score-label {
        text-align: center;
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .distribution-grid {
        display: grid;
        gap: 1rem;
    }

    .distribution-item {
        display: grid;
        grid-template-columns: 2fr 3fr 1fr;
        gap: 1rem;
        align-items: center;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
    }

    .distribution-label {
        font-size: 0.875rem;
        color: var(--text-primary);
        font-weight: 500;
    }

    .distribution-bar {
        height: 6px;
        background: var(--shadow-dark);
        border-radius: 3px;
        overflow: hidden;
    }

    .distribution-fill {
        height: 100%;
        background: var(--blue-soft);
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .distribution-count {
        font-size: 0.875rem;
        color: var(--text-secondary);
        text-align: right;
    }

    .answer-display {
        text-align: center;
    }

    .answer-value {
        display: block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        border-radius: 50%;
        font-weight: 600;
        color: white;
        margin: 0 auto 0.25rem;
    }

    .answer-1 { background: var(--red-soft); }
    .answer-2 { background: #ff9800; }
    .answer-3 { background: var(--yellow-soft); color: var(--text-primary); }
    .answer-4 { background: var(--blue-soft); }
    .answer-5 { background: var(--green-soft); }

    .answer-label {
        display: block;
        font-size: 0.7rem;
        color: var(--text-muted);
        line-height: 1.2;
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

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-select {
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

    .filter-select:focus {
        outline: none;
        box-shadow: 
            inset 3px 3px 6px var(--shadow-dark),
            inset -3px -3px 6px var(--shadow-light),
            0 0 0 2px var(--blue-soft);
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
        .category-analysis {
            grid-template-columns: 1fr;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }

        .distribution-item {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize score bars animation
    setTimeout(() => {
        const scoreFills = document.querySelectorAll('.score-fill, .distribution-fill');
        scoreFills.forEach(fill => {
            const width = fill.style.width;
            fill.style.width = '0%';
            setTimeout(() => {
                fill.style.width = width;
            }, 100);
        });
    }, 500);
});

function changeAttempt(attemptNum) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('attempt', attemptNum);
    window.location.href = currentUrl.toString();
}
</script>
@endsection