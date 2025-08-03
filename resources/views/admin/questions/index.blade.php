@extends('layouts.admin')

@section('title', 'Kelola Pertanyaan - HEI Assessment')
@section('page-title', 'Kelola Pertanyaan')
@section('page-description', 'Manajemen pertanyaan HEI Assessment')

@section('page-actions')
    <a href="{{ route('admin.questions.import.form') }}" class="btn btn-success">
        📤 Import CSV
    </a>
@endsection

@section('content')
<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['total_questions'] }}</div>
        <div class="stat-label">Total Pertanyaan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['active_questions'] }}</div>
        <div class="stat-label">Pertanyaan Aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['harmony_count'] }}</div>
        <div class="stat-label">Harmony</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['excellence_count'] }}</div>
        <div class="stat-label">Excellence</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['integrity_count'] }}</div>
        <div class="stat-label">Integrity</div>
    </div>
</div>

<!-- Manual Input Form -->
<div class="card">
    <h3 class="text-h3 mb-4">Input Pertanyaan Manual</h3>
    <div class="manual-input-form">
        <form action="{{ route('admin.questions.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="question_text" class="form-label">Pertanyaan</label>
                    <textarea 
                        name="question_text" 
                        id="question_text" 
                        class="form-control @error('question_text') is-invalid @enderror" 
                        placeholder="Masukkan teks pertanyaan..."
                        required>{{ old('question_text') }}</textarea>
                    @error('question_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">Kategori</label>
                    <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        <option value="H" {{ old('category') == 'H' ? 'selected' : '' }}>H (Harmony)</option>
                        <option value="E" {{ old('category') == 'E' ? 'selected' : '' }}>E (Excellence)</option>
                        <option value="I" {{ old('category') == 'I' ? 'selected' : '' }}>I (Integrity)</option>
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="order" class="form-label">Urutan</label>
                    <input 
                        type="number" 
                        name="order" 
                        id="order" 
                        class="form-control @error('order') is-invalid @enderror" 
                        min="1" 
                        value="{{ old('order') }}" 
                        placeholder="1"
                        required>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            id="is_active" 
                            class="form-check-input" 
                            value="1" 
                            {{ old('is_active') ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">
                            Aktif
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">➕ Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-h3">Daftar Pertanyaan</h3>
        <div class="d-flex align-items-center" style="gap: 0.75rem;">
            @if($questions->count() > 0)
                <form action="{{ route('admin.questions.clear') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus semua pertanyaan?')">🗑️ Clear All</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Filter Section -->
    @if($questions->count() > 0)
    <div class="filter-section mb-4">
        <div class="d-flex align-items-center" style="gap: 1rem; flex-wrap: wrap;">
            <div class="filter-group">
                <label for="categoryFilter" class="filter-label">Filter Kategori:</label>
                <select id="categoryFilter" class="filter-select">
                    <option value="">Semua Kategori</option>
                    <option value="H">Harmony</option>
                    <option value="E">Excellence</option>
                    <option value="I">Integrity</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="statusFilter" class="filter-label">Filter Status:</label>
                <select id="statusFilter" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>
            <div class="filter-group">
                <button type="button" id="resetFilter" class="btn btn-secondary btn-sm">🔄 Reset Filter</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Questions Table -->
    @if($questions->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Pertanyaan</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($questions as $question)
                    <tr class="question-row" 
                        data-category="{{ $question->category }}" 
                        data-status="{{ $question->is_active ? 'active' : 'inactive' }}">
                        <td>
                            <span class="badge badge-primary">{{ $question->order }}</span>
                        </td>
                        <td style="max-width: 500px;">
                            <div class="question-text">
                                <div class="question-preview">{{ Str::limit($question->question_text, 80) }}</div>
                                <div class="question-full" style="display: none;">{{ $question->question_text }}</div>
                                @if(strlen($question->question_text) > 80)
                                    <button type="button" class="btn-expand" onclick="toggleQuestionText(this)">
                                        <small>Lihat Selengkapnya</small>
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($question->category == 'H')
                                <span class="badge badge-harmony">Harmony</span>
                            @elseif($question->category == 'E')
                                <span class="badge badge-excellence">Excellence</span>
                            @else
                                <span class="badge badge-integrity">Integrity</span>
                            @endif
                        </td>
                        <td>
                            <label class="toggle-switch" data-question-id="{{ $question->id }}">
                                <input type="checkbox" {{ $question->is_active ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td>
                            <small>{{ $question->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center" style="gap: 0.5rem;">
                                <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')">🗑️ Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <h4 class="text-h4">Belum Ada Pertanyaan</h4>
            <p class="text-body">Mulai dengan mengimpor pertanyaan dari file CSV atau menambahkan secara manual</p>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 0.5fr 0.5fr;
        gap: 1rem;
        align-items: end;
    }

    .manual-input-form {
        background: rgba(168, 213, 186, 0.1);
        padding: 1.5rem;
        border-radius: 16px;
        border: 2px solid rgba(168, 213, 186, 0.3);
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: none;
        border-radius: 8px;
        background: var(--bg-card);
        box-shadow: 
            inset 3px 3px 6px var(--shadow-dark),
            inset -3px -3px 6px var(--shadow-light);
        font-family: inherit;
        font-size: 0.875rem;
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        box-shadow: 
            inset 4px 4px 8px var(--shadow-dark),
            inset -4px -4px 8px var(--shadow-light),
            0 0 0 2px var(--blue-soft);
    }

    .form-control::placeholder {
        color: var(--text-muted);
    }

    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    select.form-control {
        cursor: pointer;
    }

    .form-check {
        display: flex;
        align-items: center;
        margin-top: 0.5rem;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        margin-right: 0.5rem;
        accent-color: var(--green-soft);
        cursor: pointer;
    }

    .form-check-label {
        color: var(--text-primary);
        cursor: pointer;
        font-size: 0.875rem;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.75rem;
        color: var(--red-soft);
    }

    .is-invalid {
        box-shadow: 
            inset 3px 3px 6px var(--shadow-dark),
            inset -3px -3px 6px var(--shadow-light),
            0 0 0 2px var(--red-soft);
    }

    .filter-section {
        background: rgba(168, 200, 236, 0.05);
        padding: 1.5rem;
        border-radius: 16px;
        border: 1px solid rgba(168, 200, 236, 0.2);
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 150px;
    }

    .filter-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
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

    .question-row {
        transition: all 0.3s ease;
    }

    .question-row.hidden {
        display: none;
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

    /* Toggle Switch Styles */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--red-soft);
        transition: 0.4s;
        border-radius: 24px;
        box-shadow: 
            inset 2px 2px 4px var(--shadow-dark),
            inset -2px -2px 4px var(--shadow-light);
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
        box-shadow: 
            2px 2px 4px var(--shadow-dark),
            -2px -2px 4px var(--shadow-light);
    }

    input:checked + .toggle-slider {
        background-color: var(--green-soft);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    .toggle-switch.loading {
        opacity: 0.6;
        pointer-events: none;
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

    /* Question Text Styles */
    .question-text {
        position: relative;
    }

    .question-preview,
    .question-full {
        line-height: 1.4;
        color: var(--text-primary);
    }

    .btn-expand {
        background: none;
        border: none;
        color: var(--blue-soft);
        cursor: pointer;
        padding: 0;
        margin-top: 0.25rem;
        text-decoration: underline;
        font-size: 0.75rem;
    }

    .btn-expand:hover {
        color: var(--purple-soft);
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
        .filter-section .d-flex {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-group {
            min-width: 100%;
        }
        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
    }
</style>
@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.addEventListener('DOMContentLoaded', function() {
    
    const toggleSwitches = document.querySelectorAll('.toggle-switch'); 
    
    toggleSwitches.forEach(function(toggleSwitch) {
        const checkbox = toggleSwitch.querySelector('input[type="checkbox"]'); 
        const questionId = toggleSwitch.getAttribute('data-question-id'); 
        
        
        checkbox.addEventListener('change', function() {
            
            toggleSwitch.classList.add('loading');
            
            
            fetch(`{{ route('admin.questions.index') }}/${questionId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json()) 
            .then(data => {
                if (data.success) {
                    const row = toggleSwitch.closest('.question-row');
                    row.setAttribute('data-status', data.is_active ? 'active' : 'inactive');

                    updateStatistics();
                } else {
                    checkbox.checked = !checkbox.checked;
                    alert('Gagal mengubah status pertanyaan'); 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                checkbox.checked = !checkbox.checked;
                alert('Terjadi kesalahan saat mengubah status'); 
            })
            .finally(() => {
                toggleSwitch.classList.remove('loading');
            });
        });
    });

    
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const resetFilter = document.getElementById('resetFilter');
    const questionRows = document.querySelectorAll('.question-row');

    function applyFilters() {
        const selectedCategory = categoryFilter?.value || '';
        const selectedStatus = statusFilter?.value || '';

        questionRows.forEach(row => {
            const rowCategory = row.getAttribute('data-category');
            const rowStatus = row.getAttribute('data-status');
            let showRow = true;

            if (selectedCategory && rowCategory !== selectedCategory) showRow = false;
            if (selectedStatus && rowStatus !== selectedStatus) showRow = false;

            if (showRow) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    function resetFilters() {
        if (categoryFilter) categoryFilter.value = '';
        if (statusFilter) statusFilter.value = '';
        questionRows.forEach(row => row.classList.remove('hidden'));
    }

    if (categoryFilter) categoryFilter.addEventListener('change', applyFilters);
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (resetFilter) resetFilter.addEventListener('click', resetFilters);


    const orderInput = document.getElementById('order');
    if (orderInput && !orderInput.value) {
        const existingOrders = Array.from(document.querySelectorAll('.question-row')).map(row => {
            const badge = row.querySelector('.badge-primary');
            return badge ? parseInt(badge.textContent) : 0;
        });
        
        const maxOrder = existingOrders.length > 0 ? Math.max(...existingOrders) : 0;
        orderInput.value = maxOrder + 1;
    }


    const form = document.querySelector('.manual-input-form form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const questionText = document.getElementById('question_text').value.trim();
            const category = document.getElementById('category').value;
            const order = document.getElementById('order').value;

            if (!questionText || !category || !order) {
                e.preventDefault(); 
                alert('Harap lengkapi semua field yang wajib diisi'); 
                return false;
            }

            const existingOrders = Array.from(document.querySelectorAll('.question-row')).map(row => {
                const badge = row.querySelector('.badge-primary');
                return badge ? parseInt(badge.textContent) : 0;
            });

            if (existingOrders.includes(parseInt(order))) {
                e.preventDefault(); 
                alert(`Urutan ${order} sudah digunakan. Silakan pilih urutan lain.`); 
                document.getElementById('order').focus(); 
                return false;
            }
        });
    }
});

function updateStatistics() {
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// Function untuk toggle question text
function toggleQuestionText(button) {
    const questionText = button.closest('.question-text');
    const preview = questionText.querySelector('.question-preview');
    const full = questionText.querySelector('.question-full');
    
    if (full.style.display === 'none') {
        preview.style.display = 'none';
        full.style.display = 'block';
        button.innerHTML = '<small>Lihat Lebih Sedikit</small>';
    } else {
        preview.style.display = 'block';
        full.style.display = 'none';
        button.innerHTML = '<small>Lihat Selengkapnya</small>';
    }
}
</script>
@endsection