questions/import.blade.php

@extends('layouts.admin')

@section('title', 'Import CSV - HEI Assessment')
@section('page-title', 'Import CSV')
@section('page-description', 'Import pertanyaan dari file CSV')

@section('page-actions')
    <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
        ← Kembali ke Daftar Pertanyaan
    </a>
@endsection

@section('content')
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
    
    <form action="{{ route('admin.questions.import.preview') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
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

        <div class="form-actions">
            <button type="submit" class="btn btn-success" id="previewBtn" disabled>
                📊 Preview Data
            </button>
            <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                ❌ Batal
            </a>
        </div>
    </form>
</div>
@endsection

@section('styles')
<style>
    .format-example {
        background: rgba(168, 200, 236, 0.1);
        padding: 1.5rem;
        border-radius: 12px;
        margin: 1rem 0;
        border-left: 4px solid var(--blue-soft);
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

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-text {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(209, 210, 217, 0.3);
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
        this.classList.add('dragover');
    };

    uploadArea.ondragleave = function() {
        this.classList.remove('dragover');
    };

    uploadArea.ondrop = function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const file = e.dataTransfer.files[0];
        if (file && (file.name.endsWith('.csv') || file.name.endsWith('.txt'))) {
            fileInput.files = e.dataTransfer.files;
            handleFile(file);
        } else {
            alert('Hanya file CSV atau TXT yang diperbolehkan!');
        }
    };

    // Form validation
    const form = document.getElementById('uploadForm');
    form.addEventListener('submit', function(e) {
        if (!fileInput.files[0]) {
            e.preventDefault();
            alert('Harap pilih file CSV terlebih dahulu!');
            return false;
        }

        const file = fileInput.files[0];
        if (file.size > 2 * 1024 * 1024) { // 2MB
            e.preventDefault();
            alert('Ukuran file maksimal 2MB!');
            return false;
        }

        if (!file.name.endsWith('.csv') && !file.name.endsWith('.txt')) {
            e.preventDefault();
            alert('Hanya file CSV atau TXT yang diperbolehkan!');
            return false;
        }
    });
});
</script>
@endsection