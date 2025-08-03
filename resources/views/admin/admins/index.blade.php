@extends('layouts.admin')

@section('title', 'Kelola Admin - HEI Assessment')
@section('page-title', 'Kelola Admin')
@section('page-description', 'Manajemen akun administrator sistem')

@section('page-actions')
    <a href="{{ route('admin.admins.create') }}" class="btn btn-success">
        ➕ Tambah Admin Baru
    </a>
@endsection

@section('content')
<!-- Statistics Overview -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['total_admins'] }}</div>
        <div class="stat-label">Total Admin</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['active_admins'] }}</div>
        <div class="stat-label">Admin Aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['inactive_admins'] }}</div>
        <div class="stat-label">Admin Tidak Aktif</div>
    </div>
</div>

<!-- Admin List -->
<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-h3">Daftar Administrator</h3>
        <div class="d-flex align-items-center" style="gap: 0.75rem;">
            <div class="filter-group">
                <select id="statusFilter" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>

    @if($admins->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Terakhir Login</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    <tr class="admin-row" data-status="{{ $admin->is_active ? 'active' : 'inactive' }}">
                        <td>
                            <strong>{{ $admin->username }}</strong>
                        </td>
                        <td>{{ $admin->name }}</td>
                        <td>
                            <a href="mailto:{{ $admin->email }}" style="color: var(--blue-soft); text-decoration: none;">
                                {{ $admin->email }}
                            </a>
                        </td>
                        <td>
                            <label class="toggle-switch" data-admin-id="{{ $admin->id }}">
                                <input type="checkbox" {{ $admin->is_active ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td>
                            @if($admin->last_login_at)
                                <small>{{ $admin->last_login_at->diffForHumans() }}</small>
                                <br>
                                <small style="color: var(--text-muted);">{{ $admin->last_login_at->format('d/m/Y H:i') }}</small>
                            @else
                                <span style="color: var(--text-muted);">Belum pernah login</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $admin->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center" style="gap: 0.5rem;">
                                <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning btn-sm">
                                    ✏️ Edit
                                </a>
                                @if($stats['total_admins'] > 1)
                                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus admin {{ $admin->name }}?')">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-danger btn-sm" disabled title="Tidak dapat menghapus admin terakhir">
                                        🗑️ Hapus
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">👥</div>
            <h4 class="text-h4">Belum Ada Admin</h4>
            <p class="text-body">Mulai dengan menambahkan administrator baru untuk mengelola sistem</p>
            <a href="{{ route('admin.admins.create') }}" class="btn btn-success" style="margin-top: 1rem;">
                ➕ Tambah Admin Pertama
            </a>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 150px;
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

    .admin-row.hidden {
        display: none;
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Toggle Switch Functionality
    const toggleSwitches = document.querySelectorAll('.toggle-switch');
    
    toggleSwitches.forEach(function(toggleSwitch) {
        const checkbox = toggleSwitch.querySelector('input[type="checkbox"]');
        const adminId = toggleSwitch.getAttribute('data-admin-id');
        
        checkbox.addEventListener('change', function() {
            toggleSwitch.classList.add('loading');
            
            fetch(`{{ route('admin.admins.index') }}/${adminId}/toggle`, {
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
                    const row = toggleSwitch.closest('.admin-row');
                    row.setAttribute('data-status', data.is_active ? 'active' : 'inactive');
                    
                    // Show success message
                    showAlert('success', data.message);
                } else {
                    // Revert toggle
                    checkbox.checked = !checkbox.checked;
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                checkbox.checked = !checkbox.checked;
                showAlert('danger', 'Terjadi kesalahan saat mengubah status admin');
            })
            .finally(() => {
                toggleSwitch.classList.remove('loading');
            });
        });
    });

    // Filter Functionality
    const statusFilter = document.getElementById('statusFilter');
    const resetFilter = document.getElementById('resetFilter');
    const adminRows = document.querySelectorAll('.admin-row');

    function applyFilters() {
        const selectedStatus = statusFilter.value;

        adminRows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            let showRow = true;

            if (selectedStatus && rowStatus !== selectedStatus) showRow = false;

            if (showRow) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    function resetFilters() {
        statusFilter.value = '';
        adminRows.forEach(row => row.classList.remove('hidden'));
    }

    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (resetFilter) resetFilter.addEventListener('click', resetFilters);

    // Alert Function
    function showAlert(type, message) {
        const alertHtml = `<div class="alert alert-${type}" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            ${message}
        </div>`;
        
        document.body.insertAdjacentHTML('beforeend', alertHtml);
        
        setTimeout(() => {
            const alert = document.querySelector('.alert:last-child');
            if (alert) alert.remove();
        }, 3000);
    }
});
</script>
@endsection