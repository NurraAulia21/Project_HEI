<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pertanyaan - Dashboard Admin</title>
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
        .text-body { font-size: 1rem; font-weight: 400; line-height: 1.6; color: var(--text-secondary); }

        .container {
            max-width: 800px;
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

        .form-check {
            display: flex;
            align-items: center;
            margin-top: 1rem;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
            accent-color: var(--green-soft);
            cursor: pointer;
        }

        .form-check-label {
            color: var(--text-primary);
            cursor: pointer;
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

        .btn-primary {
            background: var(--blue-soft);
            color: white;
        }

        .btn-secondary {
            background: var(--text-muted);
            color: white;
        }

        .btn-warning {
            background: var(--yellow-soft);
            color: var(--text-primary);
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

        .alert {
            padding: 1rem 1.5rem;
            margin: 1rem 0;
            border-radius: 12px;
            font-weight: 500;
        }

        .alert-danger {
            background: var(--red-soft);
            color: white;
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
                    <h1 class="text-h1">Edit Pertanyaan</h1>
                    <p class="text-body">Edit pertanyaan HEI Assessment</p>
                </div>
                <div>
                    <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Edit -->
        <div class="section">
            <form action="{{ route('dashboard.update', $question) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="question_text" class="form-label">Pertanyaan *</label>
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
                    <label for="category" class="form-label">Kategori HEI *</label>
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
                    <label for="order" class="form-label">Urutan Pertanyaan *</label>
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

                <div class="form-group">
                    <div class="form-check">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            id="is_active" 
                            class="form-check-input" 
                            value="1" 
                            {{ old('is_active', $question->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">
                            Aktifkan Pertanyaan
                        </label>
                    </div>
                    <div class="form-text">Uncheck untuk menyembunyikan pertanyaan dari test</div>
                </div>

                <div class="d-flex justify-content-between" style="margin-top: 2rem;">
                    <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning">Update Pertanyaan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('question_text').focus();
    </script>
</body>
</html>