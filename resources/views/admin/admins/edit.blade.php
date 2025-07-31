@extends('layouts.admin')

@section('title', 'Edit Admin - HEI Assessment')
@section('page-title', 'Edit Administrator')
@section('page-description', 'Perbarui informasi akun administrator')

@section('page-actions')
    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
        ← Kembali ke Daftar Admin
    </a>
@endsection

@section('content')
<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-h3">Edit Admin: {{ $admin->name }}</h3>
        <div class="admin-info">
            <small style="color: var(--text-muted);">
                Terdaftar: {{ $admin->created_at->format('d/m/Y H:i') }}
                @if($admin->last_login_at)
                    <br>Login terakhir: {{ $admin->last_login_at->diffForHumans() }}
                @endif
            </small>
        </div>
    </div>
    
    <form action="{{ route('admin.admins.update', $admin) }}" method="POST" id="admin-form">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <!-- Username -->
            <div class="form-group">
                <label for="username" class="form-label">Username <span style="color: var(--red-soft);">*</span></label>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    class="form-control @error('username') is-invalid @enderror" 
                    value="{{ old('username', $admin->username) }}" 
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
                    value="{{ old('name', $admin->name) }}" 
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
                    value="{{ old('email', $admin->email) }}" 
                    placeholder="Masukkan email..."
                    required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text">Email harus unik dan valid</small>
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
                        {{ old('is_active', $admin->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">
                        <strong>Admin Aktif</strong>
                        <br>
                        <small style="color: var(--text-muted);">Admin aktif dapat login ke sistem</small>
                    </label>
                </div>
            </div>
        </div>

        <!-- Password Section -->
        <div class="password-section">
            <h4 class="text-h4" style="margin-bottom: 1rem;">Ubah Password</h4>
            <p class="text-body" style="color: var(--text-muted); margin-bottom: 1.5rem;">
                Kosongkan jika tidak ingin mengubah password
            </p>
            
            <div class="form-grid">
                <!-- Password Baru -->
                <div class="form-group">
                    <label for="password" class="form-label">Password Baru</label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Masukkan password baru...">
                        <button type="button" id="toggle-password" class="password-toggle">
                            👁️
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text">Minimal 6 karakter (kosongkan jika tidak ingin ubah)</small>
                </div>

                <!-- Konfirmasi Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="form-control" 
                            placeholder="Ulangi password baru...">
                        <button type="button" id="toggle-password-confirm" class="password-toggle">
                            👁️
                        </button>
                    </div>
                    <small class="form-text">Harus sama dengan password baru di atas</small>
                </div>
            </div>

            <!-- Password Strength Indicator -->
            <div class="password-strength" style="margin: 1.5rem 0; display: none;" id="password-strength">
                <div class="strength-label">Kekuatan Password:</div>
                <div class="strength-bar">
                    <div class="strength-fill" id="strength-fill"></div>
                </div>
                <div class="strength-text" id="strength-text">Masukkan password</div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                ✅ Perbarui Admin
            </button>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                ❌ Batal
            </a>
            <button type="button" class="btn btn-warning" id="reset-password-btn">
                🔄 Reset Password Default
            </button>
        </div>
    </form>
</div>

<!-- Admin Activity Summary -->
<div class="card">
    <h3 class="text-h3 mb-4">Ringkasan Aktivitas</h3>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $admin->created_at->diffInDays() }}</div>
            <div class="stat-label">Hari Bergabung</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                @if($admin->last_login_at)
                    {{ $admin->last_login_at->diffInDays() }}
                @else
                    -
                @endif
            </div>
            <div class="stat-label">Hari Terakhir Login</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <span class="badge {{ $admin->is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $admin->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
            <div class="stat-label">Status Saat Ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $admin->updated_at->format('d/m/Y') }}</div>
            <div class="stat-label">Terakhir Diperbarui</div>
        </div>
    </div>
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

    .password-section {
        background: rgba(255, 169, 129, 0.1);
        padding: 2rem;
        border-radius: 16px;
        border: 1px solid rgba(255, 169, 129, 0.3);
        margin: 2rem 0;
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
        flex-wrap: wrap;
    }

    .admin-info {
        text-align: right;
        font-size: 0.75rem;
        line-height: 1.4;
    }

    .badge-active {
        background: var(--green-soft);
        color: white;
    }

    .badge-inactive {
        background: var(--red-soft);
        color: white;
    }

    .badge {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .form-actions {
            flex-direction: column;
        }

        .d-flex {
            flex-direction: column;
            gap: 1rem;
        }

        .admin-info {
            text-align: left;
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
    const passwordStrength = document.getElementById('password-strength');

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

    // Show/Hide Password Strength Indicator
    passwordInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            passwordStrength.style.display = 'block';
            checkPasswordStrength(this.value);
        } else {
            passwordStrength.style.display = 'none';
        }
    });

    // Password Strength Checker
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');

    function checkPasswordStrength(password) {
        if (password.length === 0) {
            strengthFill.style.width = '0%';
            strengthText.textContent = 'Masukkan password';
            return;
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

        // Update UI
        strengthFill.style.width = score + '%';
        
        if (score < 30) {
            strengthFill.style.background = 'var(--red-soft)';
            strengthText.textContent = 'Lemah - ' + feedback.join(', ');
        } else if (score < 60) {
            strengthFill.style.background = 'var(--yellow-soft)';
            strengthText.textContent = 'Sedang - tambahkan huruf besar/angka/simbol';
        } else if (score < 80) {
            strengthFill.style.background = 'var(--blue-soft)';
            strengthText.textContent = 'Kuat - password bagus';
        } else {
            strengthFill.style.background = 'var(--green-soft)';
            strengthText.textContent = 'Sangat Kuat - password excellent';
        }
    }

    // Reset Password Default Button
    const resetPasswordBtn = document.getElementById('reset-password-btn');
    resetPasswordBtn.addEventListener('click', function() {
        if (confirm('Yakin ingin reset password ke default (admin123)?')) {
            passwordInput.value = 'admin123';
            passwordConfirmInput.value = 'admin123';
            checkPasswordStrength('admin123');
            passwordStrength.style.display = 'block';
        }
    });

    // Form Validation
    const form = document.getElementById('admin-form');
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const passwordConfirm = passwordConfirmInput.value;

        // Only validate if password is being changed
        if (password || passwordConfirm) {
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
        }
    });
});
</script>
@endsection