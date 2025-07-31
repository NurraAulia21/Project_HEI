@extends('layouts.app')
@include('components.navbar')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/test.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mbti.css') }}">
    <style>
        .result-page {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        .result-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .user-info {
            background: rgba(168, 200, 236, 0.1);
            padding: 1rem 2rem;
            border-radius: 16px;
            margin-bottom: 1rem;
            border: 1px solid rgba(168, 200, 236, 0.3);
        }

        .dominant-personality {
            background: var(--bg-card);
            padding: 3rem;
            border-radius: 25px;
            box-shadow: 
                12px 12px 24px var(--shadow-dark),
                -12px -12px 24px var(--shadow-light);
            text-align: center;
            margin: 2rem 0;
        }

        .personality-badge {
            display: inline-block;
            padding: 1rem 2rem;
            border-radius: 20px;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 1rem 0;
        }

        .personality-harmony { background: var(--purple-soft); color: white; }
        .personality-excellence { background: var(--blue-soft); color: white; }
        .personality-integrity { background: var(--orange-soft); color: white; }

        .personality-description {
            background: var(--bg-card);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 
                10px 10px 20px var(--shadow-dark),
                -10px -10px 20px var(--shadow-light);
            margin: 2rem 0;
        }

        .characteristics-list {
            list-style: none;
            padding: 0;
        }

        .characteristics-list li {
            padding: 0.5rem 0;
            padding-left: 1.5rem;
            position: relative;
        }

        .characteristics-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--green-soft);
            font-weight: bold;
        }

        .category-analysis {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .category-card {
            background: var(--bg-card);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 
                10px 10px 20px var(--shadow-dark),
                -10px -10px 20px var(--shadow-light);
            text-align: center;
        }

        .category-harmony { border-left: 4px solid var(--purple-soft); }
        .category-excellence { border-left: 4px solid var(--blue-soft); }
        .category-integrity { border-left: 4px solid var(--orange-soft); }

        .category-score {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 1rem 0;
        }

        .category-level {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .score-bar {
            height: 8px;
            background: var(--shadow-dark);
            border-radius: 4px;
            overflow: hidden;
            margin: 1rem 0;
        }

        .score-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.8s ease;
        }

        .score-fill-harmony { background: var(--purple-soft); }
        .score-fill-excellence { background: var(--blue-soft); }
        .score-fill-integrity { background: var(--orange-soft); }

        .answer-distribution {
            background: var(--bg-card);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 
                10px 10px 20px var(--shadow-dark),
                -10px -10px 20px var(--shadow-light);
            margin: 2rem 0;
        }

        .distribution-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(209, 210, 217, 0.3);
        }

        .distribution-item:last-child {
            border-bottom: none;
        }

        .distribution-label {
            flex: 1;
            font-weight: 500;
        }

        .distribution-bar {
            flex: 2;
            height: 6px;
            background: var(--shadow-dark);
            border-radius: 3px;
            overflow: hidden;
            margin: 0 1rem;
        }

        .distribution-fill {
            height: 100%;
            background: var(--blue-soft);
            border-radius: 3px;
            transition: width 0.5s ease;
        }

        .distribution-percentage {
            min-width: 60px;
            text-align: right;
            font-weight: 600;
            color: var(--text-primary);
        }

        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .stat-item {
            background: var(--bg-card);
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 
                8px 8px 16px var(--shadow-dark),
                -8px -8px 16px var(--shadow-light);
            text-align: center;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        .action-buttons {
            text-align: center;
            margin: 3rem 0;
        }

        .btn-primary {
            background: var(--blue-soft);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 16px;
            font-weight: 600;
            text-decoration: none;
            margin: 0.5rem;
            display: inline-block;
            box-shadow: 
                6px 6px 12px var(--shadow-dark),
                -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 
                8px 8px 16px var(--shadow-dark),
                -8px -8px 16px var(--shadow-light);
        }

        .btn-secondary {
            background: var(--purple-soft);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 16px;
            font-weight: 600;
            text-decoration: none;
            margin: 0.5rem;
            display: inline-block;
            box-shadow: 
                6px 6px 12px var(--shadow-dark),
                -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 
                8px 8px 16px var(--shadow-dark),
                -8px -8px 16px var(--shadow-light);
        }

        .attempt-selector {
            text-align: center;
            margin: 1rem 0;
        }

        .attempt-selector select {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            background: var(--bg-card);
            box-shadow: 
                inset 2px 2px 4px var(--shadow-dark),
                inset -2px -2px 4px var(--shadow-light);
        }

        @media (max-width: 768px) {
            .result-page {
                padding: 1rem;
            }
            
            .category-analysis {
                grid-template-columns: 1fr;
            }
            
            .stats-overview {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endsection

@section('content')
<div class="container result-page">
    <!-- Header -->
    <div class="result-header">
        <h1 class="text-h1">Hasil HEI Personality Assessment</h1>
        <div class="user-info">
            <strong>{{ $user->name }}</strong> ({{ $user->email }})
            <br>
            <small>Percobaan ke-{{ $attempt }} • {{ $completionDate->format('d F Y, H:i') }}</small>
        </div>
        
        @if($availableAttempts->count() > 1)
        <div class="attempt-selector">
            <label>Lihat Percobaan: </label>
            <select onchange="changeAttempt(this.value)">
                @foreach($availableAttempts as $attemptNum)
                    <option value="{{ $attemptNum }}" {{ $attempt == $attemptNum ? 'selected' : '' }}>
                        Percobaan ke-{{ $attemptNum }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    <!-- Stats Overview -->
    <!-- <div class="stats-overview">
        <div class="stat-item">
            <div class="stat-number">{{ $totalQuestions }}</div>
            <div class="stat-label">Total Jawaban</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $averageScore }}</div>
            <div class="stat-label">Rata-rata Skor</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">100%</div>
            <div class="stat-label">Penyelesaian</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $attempt }}</div>
            <div class="stat-label">Percobaan</div>
        </div>
    </div> -->

    <!-- Dominant Personality -->
    <div class="dominant-personality">
        <h2 class="text-h2">Kepribadian Dominan Anda</h2>
        <div class="personality-badge personality-{{ strtolower($dominantPersonality) }}">
            {{ $personalityDescription['title'] }}
        </div>
        <p class="text-body" style="font-size: 1.1rem; line-height: 1.6;">
            {{ $personalityDescription['description'] }}
        </p>
    </div>

    <!-- Personality Description -->
    <div class="personality-description">
        <h3 class="text-h3">Karakteristik Anda:</h3>
        <ul class="characteristics-list">
            @foreach($personalityDescription['characteristics'] as $characteristic)
                <li>{{ $characteristic }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Category Analysis -->
    <div class="section">
        <h3 class="text-h3" style="text-align: center; margin-bottom: 2rem;">Analisis per Kategori</h3>
        <div class="category-analysis">
            @foreach(['H', 'E', 'I'] as $category)
            @php $result = $categoryResults[$category]; @endphp
            <div class="category-card category-{{ strtolower($category) }}">
                <h4 class="text-h4">{{ $result['name'] }}</h4>
                <div class="category-score">{{ $result['average'] }}</div>
                <div class="category-level">{{ $result['level'] }}</div>
                <div class="score-bar">
                    <div class="score-fill score-fill-{{ strtolower($category) }}" 
                         style="width: {{ $result['percentage'] }}%"></div>
                </div>
                <small>{{ $result['count'] }} pertanyaan • {{ $result['percentage'] }}%</small>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="" class="btn-primary">🔄 Ulangi Test</a>
        <a href="" class="btn-secondary">⬅️ Kembali ke Beranda</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate score bars
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
    const currentUrl = window.location.pathname;
    const userId = currentUrl.split('/')[3]; // Extract user ID
    window.location.href = `/test/result/${userId}/${attemptNum}`;
}
</script>
@endsection