@extends('layouts.admin')

@section('title', 'Dashboard Admin - HEI Assessment')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview sistem HEI Assessment')

@section('content')
<!-- Category Distribution -->
<div class="card">
    <h3 class="text-h3 mb-4">Salam HEI!</h3>
    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, var(--purple-soft), rgba(177, 156, 217, 0.3));">
            <div class="stat-number" style="color: white;">Harmony</div>
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.8);">Hidup rukun dalam keberagaman</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, var(--blue-soft), rgba(168, 200, 236, 0.3));">
            <div class="stat-number" style="color: white;">Excellence</div>
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.8);">Selalu berusaha menjadi yang terbaik</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, var(--orange-soft), rgba(255, 169, 129, 0.3));">
            <div class="stat-number" style="color: white;">Integrity</div>
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.8);">Bertindak jujur dan bertanggung jawabntegrity</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <h3 class="text-h3 mb-4">Aksi Cepat</h3>
    <div class="d-flex" style="gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('admin.questions.index') }}" class="btn btn-success">
            ➕ Tambah Pertanyaan
        </a>
        <a href="{{ route('admin.questions.import.form') }}" class="btn btn-primary">
            📤 Import CSV
        </a>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-secondary">
            👤 Tambah Admin
        </a>
        <a href="{{ route('admin.answers.index') }}" class="btn btn-warning">
            📊 Lihat Hasil
        </a>
    </div>
</div>

<!-- Recent Activities -->
<div class="card">
    <h3 class="text-h3 mb-4">Aktivitas Terbaru</h3>
    @if($recentAnswers->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Pertanyaan</th>
                        <th>Jawaban</th>
                        <th>Kategori</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentAnswers as $answer)
                    <tr>
                        <td>
                            <strong>{{ $answer->user->name }}</strong>
                            <br>
                            <small style="color: var(--text-muted);">{{ $answer->user->email }}</small>
                        </td>
                        <td style="max-width: 300px;">
                            {{ Str::limit($answer->question->question_text, 60) }}
                        </td>
                        <td>
                            <span class="badge" style="
                                background: {{ $answer->answer_value >= 4 ? 'var(--green-soft)' : ($answer->answer_value >= 3 ? 'var(--yellow-soft)' : 'var(--red-soft)') }};
                                color: {{ $answer->answer_value >= 3 ? 'white' : 'var(--text-primary)' }};
                            ">
                                {{ $answer->answer_value }} - {{ $answer->answer_label }}
                            </span>
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
                            <small>{{ $answer->created_at->diffForHumans() }}</small>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📝</div>
            <h4 class="text-h4">Belum Ada Aktivitas</h4>
            <p class="text-body">Belum ada mahasiswa yang mengerjakan assessment</p>
        </div>
    @endif
</div>

@endsection

@section('styles')
<style>
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

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }

    .empty-icon {
        font-size: 3rem;
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
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .badge {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
        }
    }
</style>
@endsection

@section('scripts')
@if($chartData->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('activityChart').getContext('2d');
    
    const chartData = @json($chartData);
    const labels = chartData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    });
    const data = chartData.map(item => item.count);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jawaban per Hari',
                data: data,
                borderColor: 'var(--blue-soft)',
                backgroundColor: 'rgba(168, 200, 236, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'var(--blue-soft)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(209, 210, 217, 0.3)',
                    },
                    ticks: {
                        color: 'var(--text-secondary)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(209, 210, 217, 0.3)',
                    },
                    ticks: {
                        color: 'var(--text-secondary)'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'var(--text-primary)'
                    }
                }
            }
        }
    });
});
</script>
@endif
@endsection