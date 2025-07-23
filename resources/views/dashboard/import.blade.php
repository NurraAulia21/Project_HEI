<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import CSV - Dashboard Admin</title>
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
            max-width: 900px;
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

        .form-text {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
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

        .code-block {
            background: var(--bg-card);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 
                inset 4px 4px 8px var(--shadow-dark),
                inset -4px -4px 8px var(--shadow-light);
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            color: var(--text-primary);
            overflow-x: auto;
            margin: 1rem 0;
        }

        .format-example {
            background: rgba(168, 200, 236, 0.1);
            padding: 1.5rem;
            border-radius: 12px;
            margin: 1rem 0;
            border-left: 4px solid var(--blue-soft);
        }

        .file-upload-area {
            border: 2px dashed var(--text-muted);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .file-upload-area:hover {
            border-color: var(--blue-soft);
            background: rgba(168, 200, 236, 0.05);
        }

        .file-upload-area.dragover {
            border-color: var(--green-soft);
            background: rgba(168, 213, 186, 0.1);
        }

        .file-upload-icon {
            font-size: 3rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
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
                    <h1 class="text-h1">Import CSV</h1>
                    <p class="text-body">Import pertanyaan dari file CSV</p>
                </div>
                <div>
                    <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <h3 class="text-h3" style="margin-bottom: 1rem;">Format File CSV</h3>
            <p class="text-body">File CSV harus mengikuti format berikut:</p>
            
            <div class="format-example">
                <strong>Header (baris pertama):</strong>
                <div class="code-block">question_text;category;order;is_active</div>
                
                <strong>Data (baris selanjutnya):</strong>
                <div class="code-block">Saya lebih suka bekerja dalam tim;H;1;true<br>Saya selalu berusaha memberikan yang terbaik;E;2;true<br>Saya berpegang teguh pada prinsip yang benar;I;3;false</div>
            </div>

            <div style="margin-top: 1.5rem;">
                <h4 style="color: var(--text-primary); margin-bottom: 0.5rem;">Keterangan Kolom:</h4>
                <ul style="color: var(--text-secondary); padding-left: 1.5rem;">
                    <li><strong>question_text:</strong> Teks pertanyaan (wajib diisi)</li>
                    <li><strong>category:</strong> Kategori HEI - H/E/I (wajib diisi)</li>
                    <li><strong>order:</strong> Urutan pertanyaan - angka (wajib diisi, harus unik)</li>
                    <li><strong>is_active:</strong> Status aktif - true/false (opsional, default: true)</li>
                </ul>
            </div>
        </div>

        <div class="card">
            <h3 class="text-h3" style="margin-bottom: 1.5rem;">Upload File CSV</h3>
            
            <form action="{{ route('dashboard.import.preview') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                
                <div class="form-group">
                    <div class="file-upload-area" id="fileUploadArea">
                        <div class="file-upload-icon">📁</div>
                        <h4 style="color: var(--text-primary); margin-bottom: 0.5rem;">Pilih File CSV</h4>
                        <p class="text-body">Drag & drop file CSV atau klik untuk memilih</p>
                        <input type="file" name="csv_file" id="csv_file" class="file-input" accept=".csv,.txt" required>
                    </div>
                    <div class="form-text">File maksimal 2MB dengan format .csv atau .txt</div>
                </div>

                <div id="fileInfo" style="display: none; margin-top: 1rem; padding: 1rem; background: rgba(168, 213, 186, 0.1); border-radius: 8px;">
                    <strong>File terpilih:</strong> <span id="fileName"></span>
                </div>

                <div class="d-flex justify-content-between" style="margin-top: 2rem;">
                    <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success" id="previewBtn" disabled>Preview Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('csv_file');
        const uploadArea = document.getElementById('fileUploadArea');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const previewBtn = document.getElementById('previewBtn');

        function handleFile(file) {
            fileName.textContent = file.name;
            fileInfo.style.display = 'block';
            previewBtn.disabled = false;
            uploadArea.style.borderColor = 'var(--green-soft)';
            uploadArea.style.backgroundColor = 'rgba(168, 213, 186, 0.1)';
        }

        fileInput.onchange = function() {
            if (this.files[0]) handleFile(this.files[0]);
        };

        uploadArea.onclick = function(e) {
            if (e.target !== fileInput) {
                fileInput.click();
            }
        };

        fileInput.onclick = function(e) {
            e.stopPropagation();
        };

        uploadArea.ondragover = function(e) {
            e.preventDefault();
            this.style.backgroundColor = 'rgba(168, 213, 186, 0.05)';
        };

        uploadArea.ondragleave = function() {
            this.style.backgroundColor = '';
        };

        uploadArea.ondrop = function(e) {
            e.preventDefault();
            this.style.backgroundColor = '';
            
            const file = e.dataTransfer.files[0];
            if (file && (file.name.endsWith('.csv') || file.name.endsWith('.txt'))) {
                fileInput.files = e.dataTransfer.files;
                handleFile(file);
            } else {
                alert('Hanya file CSV atau TXT yang diperbolehkan!');
            }
        };

    </script>
</body>
</html>