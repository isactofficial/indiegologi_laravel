@extends('layouts.admin')

@section('content')
<style>
    :root {
        --theme-primary: #0C2C5A;
        --theme-accent: #f4b704;
        --theme-danger: #cb2786;
    }

    .btn-sporty-primary {
        background-color: var(--theme-primary);
        border-color: var(--theme-primary);
        color: white;
        border-radius: 0.75rem;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 8px rgba(12, 44, 90, 0.2);
    }

    .btn-sporty-primary:hover {
        background-color: #081f3f;
        border-color: #081f3f;
        transform: translateY(-2px);
        color: white;
    }

    .alert-custom-success {
        background-color: rgba(12, 44, 90, 0.1);
        color: var(--theme-primary);
        border: 1px solid rgba(12, 44, 90, 0.3);
    }

    .badge-status-active {
        background-color: rgba(0, 97, 122, 0.15);
        color: #00617a;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
    }

    .badge-status-inactive {
        background-color: rgba(203, 39, 134, 0.15);
        color: #cb2786;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
    }

    .mobile-cards {
        display: none;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }

        .table-responsive {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .type-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #f0f0f0;
        }
        
        .type-card:last-child {
            margin-bottom: 0;
        }
    }
</style>

<div class="container-fluid px-4" style="min-height: 100vh;">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid var(--theme-primary);">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-calendar-check fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Konsultasi Gratis</h2>
                        <p class="text-muted mb-0">Kelola jenis dan jadwal konsultasi gratis di sini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Tabs --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.free-consultation.types.index') }}">
                        <i class="fas fa-list me-2"></i>Jenis Konsultasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.free-consultation.schedules.index') }}">
                        <i class="fas fa-calendar-alt me-2"></i>Jadwal Konsultasi
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end">
            <a href="{{ route('admin.free-consultation.types.create') }}" class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Tambah Jenis</span>
            </a>
        </div>
    </div>

    {{-- Table & Cards Container --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success rounded-3 alert-custom-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger rounded-3 mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            {{-- [DESKTOP] Tampilan Tabel --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="py-3">ID</th>
                            <th class="py-3">Nama</th>
                            <th class="py-3">Deskripsi</th>
                            <th class="py-3">Jumlah Jadwal</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($types as $type)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="py-3">{{ $type->id }}</td>
                                <td class="py-3 fw-semibold text-break" style="color: var(--theme-primary);">{{ $type->name }}</td>
                                <td class="py-3">
                                    <div class="text-muted small" style="line-height: 1.4; max-width: 300px;">{{ Str::limit($type->description, 100) }}</div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-info">{{ $type->schedules->count() }} jadwal</span>
                                </td>
                                <td class="py-3">
                                    @php
                                        $statusClass = $type->status === 'active' ? 'badge-status-active' : 'badge-status-inactive';
                                        $statusText = $type->status === 'active' ? 'Aktif' : 'Nonaktif';
                                        $statusIcon = $type->status === 'active' ? 'fa-check' : 'fa-times';
                                    @endphp
                                    <span class="badge {{ $statusClass }}"><i class="fas {{ $statusIcon }} me-1"></i>{{ $statusText }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.free-consultation.types.show', $type) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" style="border-color: var(--theme-primary); color: var(--theme-primary);" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.free-consultation.types.edit', $type) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color: var(--theme-accent); color: var(--theme-accent);" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.free-consultation.types.destroy', $type) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: var(--theme-danger); color: var(--theme-danger);" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted"><i class="fas fa-inbox me-2"></i>Tidak ada jenis konsultasi gratis.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- [MOBILE] Tampilan Kartu --}}
            <div class="mobile-cards">
                @forelse($types as $type)
                    <div class="type-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold" style="color: var(--theme-primary);">{{ $type->name }}</h5>
                            @php
                                $statusClass = $type->status === 'active' ? 'badge-status-active' : 'badge-status-inactive';
                                $statusText = $type->status === 'active' ? 'Aktif' : 'Nonaktif';
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                        </div>
                        <p class="text-muted small mb-3">{{ Str::limit($type->description, 150) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-info">{{ $type->schedules->count() }} jadwal</span>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.free-consultation.types.show', $type) }}" class="btn btn-sm btn-outline-info" style="border-color: var(--theme-primary); color: var(--theme-primary);"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.free-consultation.types.edit', $type) }}" class="btn btn-sm btn-outline-secondary" style="border-color: var(--theme-accent); color: var(--theme-accent);"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.free-consultation.types.destroy', $type) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger" style="border-color: var(--theme-danger); color: var(--theme-danger);"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted"><i class="fas fa-inbox me-2"></i>Tidak ada jenis konsultasi gratis.</div>
                @endforelse
            </div>

            @if($types->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $types->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: "Yakin ingin menghapus?",
            text: "Jenis konsultasi dan semua jadwalnya akan dihapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#cb2786",
            cancelButtonColor: "#808080",
            confirmButtonText: "Ya, Hapus Sekarang!",
            cancelButtonText: "Batalkan",
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'rounded-pill px-4',
                cancelButton: 'rounded-pill px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection

