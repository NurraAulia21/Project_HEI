@extends('layouts.admin')

@section('title', 'Edit Pertanyaan - HEI Assessment')
@section('page-title', 'Edit Pertanyaan')
@section('page-description', 'Edit pertanyaan HEI Assessment')

@section('page-actions')
    <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
        ← Kembali ke Daftar Pertanyaan
    </a>
@endsection

@section('content')
<div class="card">
    <div class="info-box">
        <p class="info-text">
            <strong>💡 Info:</strong> Status aktif/non-aktif pertanyaan dapat diubah langsung di halaman dashboard menggunakan toggle switch pada tabel.
        </p>
    </div>

    <form action="{{ route('admin.questions.update', $question) }}" method="POST" id="question-form">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="question_text" class="form-label">Pertanyaan <span style="color: var(--red-soft);">*</span></label>
            <textarea 
                name="question_text" 
                id="question_text" 
                class="form-control @error('question_text') is-invalid @enderror" 
                placeholder="Masukkan teks pertanyaan HEI..."
                required>{{ old('question_text', $question->question_text) }}</textarea>
            @error('question_text')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="category" class="form-label">Kategori HEI <span style="color: var(--red-soft);">*</span></label>
            <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                <option value="">Pilih Kategori</option>
                <option value="H" {{ old('category', $question->category) == 'H' ? 'selected' : '' }}>H (Harmony)</option>
                <option value="E" {{ old('category', $question->category) == 'E' ? 'selected' : '' }}>E (Excellence)</option>
                <option value="I" {{ old('category', $question->category) == 'I' ? 'selected' : '' }}>I (Integrity)</option>
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="order" class="form-label">Urutan Pertanyaan <span style="color: var(--red-soft);">*</span></label>
            <input 
                type="number" 
                name="order" 
                id="order" 
                class="form-control @error('order') is-invalid @enderror" 
                min="1" 
                value="{{ old('order', $question->order) }}" 
                required>
            <div class="form-text">Urutan pertanyaan dalam assessment (minimal 1)</div>
            @error('order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                ✅ Update Pertanyaan
            </button>
            <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                ❌ Batal
            </a>
        </div>
    </form>
</div>

<!-- Question Information -->
<div class="card">
    <h3 class="text-h3 mb-4">Informasi Pertanyaan</h3>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $question->order }}</div>
            <div class="stat-label">Urutan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                @if($question->category == 'H')
                    <span class="badge badge-harmony">Harmony</span>
                @elseif($question->category == 'E')
                    <span class="badge badge-excellence">Excellence</span>
                @else
                    <span class="badge badge-integrity">Integrity</span>
                @endif
            </div>
            <div class="stat-label">Kategori</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <span class="badge {{ $question->is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $question->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
            <div class="stat-label">Status</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $question->created_at->format('d/m/Y') }}</div>
            <div class="stat-label">Dibuat</div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border: none;
        border-radius: 12px;
        background: var(--bg-card);
        box-shadow: 
            inset 4px 4px 8px var(--shadow-dark),
            inset -4px -4px 8px var(--shadow-light);
        font-family: inherit;
        font-size: 1rem;
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        box-shadow: 
            inset 6px 6px 12px var(--shadow-dark),
            inset -6px -6px 12px var(--shadow-light),
            0 0 0 2px var(--blue-soft);
    }

    .form-control::placeholder {
        color: var(--text-muted);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    select.form-control {
        cursor: pointer;
    }

    .form-text {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: var(--red-soft);
    }

    .is-invalid {
        box-shadow: 
            inset 4px 4px 8px var(--shadow-dark),
            inset -4px -4px 8px var(--shadow-light),
            0 0 0 2px var(--red-soft);
    }

    .info-box {
        background: rgba(168, 200, 236, 0.1);
        border: 1px solid rgba(168, 200, 236, 0.3);
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .info-text {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(209, 210, 217, 0.3);
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

    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto focus on question text
    document.getElementById('question_text').focus();

    // Form validation
    const form = document.getElementById('question-form');
    form.addEventListener('submit', function(e) {
        const questionText = document.getElementById('question_text').value.trim();
        const category = document.getElementById('category').value;
        const order = document.getElementById('order').value;

        if (!questionText || !category || !order) {
            e.preventDefault();
            alert('Harap lengkapi semua field yang wajib diisi');
            return false;
        }

        if (order < 1) {
            e.preventDefault();
            alert('Urutan pertanyaan minimal 1');
            document.getElementById('order').focus();
            return false;
        }
    });
});
</script>
@endsection