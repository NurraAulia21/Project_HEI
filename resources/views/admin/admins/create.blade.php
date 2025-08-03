@extends('layouts.admin')

@section('title', 'Tambah Admin - HEI Assessment')
@section('page-title', 'Tambah Admin Baru')
@section('page-description', 'Buat akun administrator baru untuk sistem')

@section('page-actions')
    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
        ← Kembali ke Daftar Admin
    </a>
@endsection

@section('content')
<div class="card">
    <h3 class="text-h3 mb-4">Form Tambah Administrator</h3>
    
    <form action="{{ route('admin.admins.store') }}" method="POST" id="admin-form">
        @csrf
        
        <div class="form-grid">
            <!-- Username -->
            <div class="form-group">
                <label for="username" class="form-label">Username <span style="color: var(--red-soft);">*</span></label>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    class="form-control @error('username') is-invalid @enderror" 
                    value="{{ old('username') }}" 
                    placeholder="Masukkan username..."
                    required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text">Username harus unik dan akan digunakan untuk login</small>
            </div>

            <!-- Nama Lengkap -->
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap <span style="color: var(--red-soft);">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" 
                    placeholder="Masukkan nama lengkap..."
                    required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Email <span style="color: var(--red-soft);">*</span></label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email') }}" 
                    placeholder="Masukkan email..."
                    required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text">Email harus unik dan valid</small>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Password <span style="color: var(--red-soft);">*</span></label>
                <div style="position: relative;">
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="Masukkan password..."
                        required>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text">Minimal 6 karakter</small>
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password <span style="color: var(--red-soft);">*</span></label>
                <div style="position: relative;">
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="form-control" 
                        placeholder="Ulangi password..."
                        required>
                </div>
                <small class="form-text">Harus sama dengan password di atas</small>
            </div>

            <!-- Status Aktif -->
            <div class="form-group">
                <div class="form-check" style="margin-top: 2rem;">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        id="is_active" 
                        class="form-check-input" 
                        value="1" 
                        {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">
                        <strong>Admin Aktif</strong>
                        <br>
                        <small style="color: var(--text-muted);">Admin aktif dapat login ke sistem</small>
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                ✅ Simpan Admin
            </button>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                ❌ Batal
            </a>
        </div>
    </form>
</div>
@endsection

@section('styles')
<style>
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .form-control {
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

    .form-text {
        margin-top: 0.25rem;
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .invalid-feedback {
        display: block;
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

    .form-check {
        display: flex;
        align-items: flex-start;
        margin-top: 0.5rem;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        margin-right: 0.75rem;
        margin-top: 0.125rem;
        accent-color: var(--green-soft);
        cursor: pointer;
    }

    .form-check-label {
        color: var(--text-primary);
        cursor: pointer;
        font-size: 0.875rem;
        line-height: 1.4;
    }

    .password-toggle {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        color: var(--text-muted);
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: var(--text-primary);
    }

    .password-strength {
        background: rgba(168, 200, 236, 0.1);
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid rgba(168, 200, 236, 0.3);
    }

    .strength-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .strength-bar {
        height: 6px;
        background: var(--shadow-dark);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        border-radius: 3px;
        transition: all 0.3s ease;
        background: var(--red-soft);
    }

    .strength-text {
        font-size: 0.75rem;
        color: var(--text-muted);
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
        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password Toggle Functionality
    const togglePassword = document.getElementById('toggle-password');
    const togglePasswordConfirm = document.getElementById('toggle-password-confirm');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '🙈';
    });

    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '🙈';
    });

    // Password Strength Checker
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);
        
        strengthFill.style.width = strength.percentage + '%';
        strengthFill.style.background = strength.color;
        strengthText.textContent = strength.text;
    });

    function calculatePasswordStrength(password) {
        if (password.length === 0) {
            return { percentage: 0, color: 'var(--red-soft)', text: 'Masukkan password' };
        }
        
        let score = 0;
        let feedback = [];

        // Length check
        if (password.length >= 6) score += 20;
        else feedback.push('minimal 6 karakter');

        if (password.length >= 8) score += 10;
        
        // Character variety checks
        if (/[a-z]/.test(password)) score += 15;
        if (/[A-Z]/.test(password)) score += 15;
        if (/[0-9]/.test(password)) score += 15;
        if (/[^A-Za-z0-9]/.test(password)) score += 25;

        // Determine strength level
        if (score < 30) {
            return { percentage: score, color: 'var(--red-soft)', text: 'Lemah - ' + feedback.join(', ') };
        } else if (score < 60) {
            return { percentage: score, color: 'var(--yellow-soft)', text: 'Sedang - tambahkan huruf besar/angka/simbol' };
        } else if (score < 80) {
            return { percentage: score, color: 'var(--blue-soft)', text: 'Kuat - password bagus' };
        } else {
            return { percentage: score, color: 'var(--green-soft)', text: 'Sangat Kuat - password excellent' };
        }
    }

    // Form Validation
    const form = document.getElementById('admin-form');
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const passwordConfirm = passwordConfirmInput.value;

        if (password !== passwordConfirm) {
            e.preventDefault();
            alert('Konfirmasi password tidak sama dengan password!');
            passwordConfirmInput.focus();
            return false;
        }

        if (password.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            passwordInput.focus();
            return false;
        }
    });

    // Auto-generate username from name
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    
    nameInput.addEventListener('input', function() {
        if (!usernameInput.value) {
            const username = this.value
                .toLowerCase()
                .replace(/\s+/g, '')
                .replace(/[^a-z0-9]/g, '');
            usernameInput.value = username;
        }
    });
});
</script>
@endsection