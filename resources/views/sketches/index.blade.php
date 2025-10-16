@extends('layouts.admin')

@section('content')
<style>
    :root {
        --theme-primary: #0C2C5A;
        --theme-accent: #f4b704;
        --theme-danger: #cb2786;
    }

    /* General button styling */
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

    /* Alert and Badge styling */
    .alert-custom-success {
        background-color: rgba(12, 44, 90, 0.1);
        color: var(--theme-primary);
        border: 1px solid rgba(12, 44, 90, 0.3);
    }

    .badge-status-published {
        background-color: rgba(0, 97, 122, 0.15);
        color: #00617a;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
    }

    .badge-status-draft {
        background-color: rgba(203, 39, 134, 0.15);
        color: #cb2786;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
    }

    /* --- Responsive Styles --- */
    @media (max-width: 768px) {
        /* PERBAIKAN: Menyesuaikan padding container agar kartu lebih lebar */
        .container-fluid {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
        .card-body {
            padding: 1rem !important;
        }

        .page-header .d-flex {
            flex-direction: column;
            align-items: center !important;
            text-align: center;
        }
        .page-header .rounded-circle {
            margin-right: 0 !important;
            margin-bottom: 1rem;
        }
        .add-button-container .btn-sporty-primary {
            width: 100%;
            justify-content: center;
        }

        .table-responsive {
            display: none;
        }

        .mobile-sketch-cards {
            display: block;
        }

        .sketch-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #f0f0f0;
        }

        .sketch-thumbnail {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .sketch-thumbnail-placeholder {
            width: 100%;
            height: 180px;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            border: 2px dashed #dee2e6;
        }

        .sketch-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--theme-primary);
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .sketch-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .sketch-author {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .sketch-actions {
            display: flex;
            gap: 0.5rem;
        }

        .sketch-actions .btn, .sketch-actions form {
            flex: 1;
        }
        .sketch-actions .btn {
            width: 100%;
            border-radius: 0.5rem;
        }
    }

    /* PERBAIKAN: Penyesuaian untuk layar sangat kecil (extra small) */
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        .card-body {
            padding: 0.75rem !important;
        }
        .sketch-card {
            padding: 0.75rem;
        }
        .sketch-title {
            font-size: 1rem;
        }
    }

    @media (min-width: 769px) {
        .mobile-sketch-cards {
            display: none;
        }
    }
</style>

{{-- PERBAIKAN: Menghapus class padding bawaan agar dikontrol penuh oleh CSS --}}
<div class="container-fluid" style="min-height: 100vh;">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid var(--theme-primary);">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-palette fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Painting</h2>
                        <p class="text-muted mb-0">Kelola koleksi painting Anda di sini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 add-button-container d-flex justify-content-end">
            <a href="{{ route('admin.sketches.create') }}" class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Tambah Painting Baru</span>
            </a>
        </div>
    </div>

    {{-- Table Container --}}
    <div class="card border-0 rounded-4 shadow-sm">
        {{-- PERBAIKAN: Menghapus class padding bawaan --}}
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success rounded-3 alert-custom-success mb-4"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif

            {{-- Desktop Table View --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="py-3">Thumbnail</th>
                            <th class="py-3">Judul Painting</th>
                            <th class="py-3">Penulis</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sketches as $sketch)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="py-3">
                                    @if($sketch->thumbnail)
                                        <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" class="rounded-3 object-fit-cover shadow-sm" style="width: 150px; height: 100px; border: 1px solid #eee;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex justify-content-center align-items-center shadow-sm" style="width: 150px; height: 100px; border: 1px dashed #ccc;">
                                            <span class="text-muted small">Tanpa Gambar</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-semibold text-break" style="color: var(--theme-primary);">{{ $sketch->title }}</td>
                                <td class="py-3">{{ $sketch->author }}</td>
                                <td class="py-3">
                                    @php
                                        $statusClass = $sketch->status == 'Published' ? 'badge-status-published' : 'badge-status-draft';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $sketch->status }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.sketches.show', $sketch->slug) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" style="border-color: var(--theme-primary); color: var(--theme-primary);" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.sketches.edit', $sketch->slug) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color: var(--theme-accent); color: var(--theme-accent);" title="Edit Sketsa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.sketches.destroy', $sketch->slug) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: var(--theme-danger); color: var(--theme-danger);" title="Hapus Sketsa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-box-open me-2"></i>Tidak ada sketsa yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards View --}}
            <div class="mobile-sketch-cards">
                @forelse($sketches as $sketch)
                    <div class="sketch-card">
                        @if($sketch->thumbnail)
                            <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" class="sketch-thumbnail">
                        @else
                            <div class="sketch-thumbnail-placeholder">
                                <span class="text-muted">Tanpa Gambar</span>
                            </div>
                        @endif

                        <div class="sketch-title">{{ $sketch->title }}</div>

                        <div class="sketch-meta">
                            <div class="sketch-author">
                                <i class="fas fa-user-edit me-1"></i>{{ $sketch->author }}
                            </div>
                            <div>
                                @php
                                    $statusClass = $sketch->status == 'Published' ? 'badge-status-published' : 'badge-status-draft';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $sketch->status }}</span>
                            </div>
                        </div>

                        <div class="sketch-actions">
                            <a href="{{ route('admin.sketches.show', $sketch->slug) }}" class="btn btn-sm btn-outline-info" style="border-color: var(--theme-primary); color: var(--theme-primary);" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.sketches.edit', $sketch->slug) }}" class="btn btn-sm btn-outline-secondary" style="border-color: var(--theme-accent); color: var(--theme-accent);" title="Edit Sketsa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.sketches.destroy', $sketch->slug) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger w-100" style="border-color: var(--theme-danger); color: var(--theme-danger);" title="Hapus Sketsa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-box-open me-2"></i>Tidak ada sketsa yang ditemukan.
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $sketches->links() }}
            </div>

        </div>
    </div>
</div>

<script>
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: "Yakin ingin menghapus sketsa ini?",
            text: "Anda tidak akan bisa mengembalikannya!",
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
