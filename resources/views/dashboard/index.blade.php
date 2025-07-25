<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - HEI Assessment</title>
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
            font-size: 1rem;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 
                8px 8px 16px var(--shadow-dark),
                -8px -8px 16px var(--shadow-light);
        }

        .btn-primary {
            background: var(--blue-soft);
            color: white;
        }

        .btn-success {
            background: var(--green-soft);
            color: white;
        }

        .btn-warning {
            background: var(--yellow-soft);
            color: var(--text-primary);
        }

        .btn-danger {
            background: var(--red-soft);
            color: white;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.875rem;
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

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .mb-4 {
            margin-bottom: 2rem;
        }

        .me-2 {
            margin-right: 0.5rem;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: var(--bg-card);
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 
                8px 8px 16px var(--shadow-dark),
                -8px -8px 16px var(--shadow-light);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
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

        @media (max-width: 768px) {
            .text-h1 { font-size: 2rem; }
            .text-h2 { font-size: 1.75rem; }
            body { padding: 1rem; }
            .table-responsive {
                overflow-x: auto;
            }
            .d-flex {
                flex-direction: column;
                gap: 1rem;
            }
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
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-h1">Dashboard Admin</h1>
                    <p class="text-body">Kelola Pertanyaan HEI Assessment</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $questions->count() }}</div>
                <div class="stat-label">Total Pertanyaan</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $questions->where('is_active', true)->count() }}</div>
                <div class="stat-label">Pertanyaan Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $questions->where('category', 'H')->count() }}</div>
                <div class="stat-label">Harmony</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $questions->where('category', 'E')->count() }}</div>
                <div class="stat-label">Excellence</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $questions->where('category', 'I')->count() }}</div>
                <div class="stat-label">Integrity</div>
            </div>
        </div>

        <!-- Manual Input Form -->
        <div class="card">
            <h3 class="text-h3 mb-4">Input Pertanyaan Manual</h3>
            <div class="manual-input-form">
                <form action="{{ route('dashboard.store') }}" method="POST">
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
                <h3 class="text-h3">Kelola Pertanyaan</h3>
                <div class="d-flex align-items-center" style="gap: 0.75rem;">
                    <a href="{{ route('dashboard.import.form') }}" class="btn btn-success">📤 Import CSV</a>
                    @if($questions->count() > 0)
                        <form action="{{ route('dashboard.clear') }}" method="POST" class="d-inline">
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
                                <td style="max-width: 400px;">
                                    {{ Str::limit($question->question_text, 80) }}
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
                                    <div class="d-flex align-items-center" style="gap: 0.5rem;">
                                        <a href="{{ route('dashboard.edit', $question) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                                        <form action="{{ route('dashboard.destroy', $question) }}" method="POST" class="d-inline">
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
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener('DOMContentLoaded', function() {
            
            const toggleSwitches = document.querySelectorAll('.toggle-switch'); 
            
            toggleSwitches.forEach(function(toggleSwitch) {
                const checkbox = toggleSwitch.querySelector('input[type="checkbox"]'); 
                const questionId = toggleSwitch.getAttribute('data-question-id'); 
                
                
                checkbox.addEventListener('change', function() {
                    
                    toggleSwitch.classList.add('loading');
                    
                    
                    fetch(`{{ route('dashboard.index') }}/${questionId}/toggle`, {
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
    </script>
</body>
</html>