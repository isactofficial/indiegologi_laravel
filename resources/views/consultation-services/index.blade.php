@extends('layouts.admin')

@section('content')
{{-- Palet warna utama untuk konsistensi --}}
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

    /* --- Responsive Styles --- */
    @media (max-width: 768px) {
        /* Sembunyikan tabel di mobile */
        .table-responsive {
            display: none;
        }
        /* Tampilkan card di mobile */
        .mobile-service-cards {
            display: block;
        }
        /* Penyesuaian padding */
        .card-body { padding: 1rem !important; }
        .container-fluid {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .service-card {
            background: white;
            border-radius: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #f0f0f0;
            overflow: hidden;
        }
        .service-thumbnail {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .service-thumbnail-placeholder {
            width: 100%;
            height: 180px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #f0f0f0;
        }
        .service-card-content {
            padding: 1rem;
        }
        .service-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--theme-primary);
            margin-bottom: 0.75rem;
        }
        .service-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .service-price {
            font-weight: bold;
            color: var(--theme-primary);
        }
        .service-actions {
            display: flex;
            gap: 0.5rem;
        }
        .service-actions .btn, .service-actions form {
            flex: 1;
        }
    }

    @media (min-width: 769px) {
        .mobile-service-cards {
            display: none;
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
                        <i class="fas fa-handshake fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Layanan</h2>
                        <p class="text-muted mb-0">Kelola semua layanan konsultasi yang tersedia.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end">
            <a href="{{ route('admin.consultation-services.create') }}" class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Tambah Layanan Baru</span>
            </a>
        </div>
    </div>

    {{-- Container --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-custom-success rounded-3 mb-4"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif

            {{-- Desktop Table View --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Thumbnail</th>
                            <th class="py-3">Judul Layanan</th>
                            <th class="py-3">Harga Dasar</th>
                            <th class="py-3">Harga Per Jam</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td class="py-3">
                                    @if($service->thumbnail)
                                        <img src="{{ asset('storage/' . $service->thumbnail) }}" alt="{{ $service->title }}" class="rounded-3 object-fit-cover shadow-sm" style="width: 120px; height: 80px;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex justify-content-center align-items-center" style="width: 120px; height: 80px;">
                                            <span class="text-muted small">No Image</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-semibold" style="color: var(--theme-primary);">{{ $service->title }}</td>
                                <td class="py-3">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                                <td class="py-3">Rp {{ number_format($service->hourly_price, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    @php
                                        $statusClass = 'bg-secondary';
                                        if ($service->status === 'published') $statusClass = 'bg-success';
                                        if ($service->status === 'special') $statusClass = 'bg-primary';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($service->status) }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        {{-- [DIKEMBALIKAN] Warna ikon aksi seperti semula --}}
                                        <a href="{{ route('admin.consultation-services.show', $service->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" style="border-color: #00617a; color: #00617a;" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.consultation-services.edit', $service->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color: #f4b704; color: #f4b704;" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.consultation-services.destroy', $service->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: #cb2786; color: #cb2786;" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-box-open me-2"></i>Tidak ada layanan yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards View --}}
            <div class="mobile-service-cards">
                @forelse($services as $service)
                    <div class="service-card">
                        @if($service->thumbnail)
                            <img src="{{ asset('storage/' . $service->thumbnail) }}" alt="{{ $service->title }}" class="service-thumbnail">
                        @else
                            <div class="service-thumbnail-placeholder">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif
                        <div class="service-card-content">
                            <div class="service-title">{{ $service->title }}</div>
                            <div class="service-meta">
                                <span class="service-price">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                @php
                                    $statusClass = 'bg-secondary';
                                    if ($service->status === 'published') $statusClass = 'bg-success';
                                    if ($service->status === 'special') $statusClass = 'bg-primary';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($service->status) }}</span>
                            </div>
                            <div class="service-actions">
                                <a href="{{ route('admin.consultation-services.show', $service->id) }}" class="btn btn-sm btn-outline-info" style="border-color: #00617a; color: #00617a;" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.consultation-services.edit', $service->id) }}" class="btn btn-sm btn-outline-secondary" style="border-color: #f4b704; color: #f4b704;" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.consultation-services.destroy', $service->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger w-100" style="border-color: #cb2786; color: #cb2786;" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-box-open me-2"></i>Tidak ada layanan yang ditemukan.
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</div>
<script>
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: "Yakin ingin menghapus layanan ini?",
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
