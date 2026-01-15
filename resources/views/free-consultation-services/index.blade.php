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
</style>

<div class="container-fluid px-4" style="min-height: 100vh;">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid var(--theme-primary);">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-hand-holding-heart fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Konsultasi Gratis</h2>
                        <p class="text-muted mb-0">Kelola semua layanan konsultasi gratis yang tersedia.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end">
            <a href="{{ route('admin.free-consultation.types.create') }}" class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
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
                            <th class="py-3">Nama Layanan</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Deskripsi</th>
                            <th class="py-3">Harga Original</th>
                            <th class="py-3">Terakhir Diubah</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consultationType as $type)
                            <tr>
                                <td class="py-3 fw-semibold" style="color: var(--theme-primary);">{{ $type->name }}</td>
                                <td class="py-3">
                                    @php
                                        $statusClass = 'bg-secondary';
                                        if ($type->status === 'active') $statusClass = 'bg-success';
                                        if ($type->status === 'inactive') $statusClass = 'bg-danger';
                                        @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($type->status) }}</span>
                                </td>
                                <td class="py-3 pe-3 w-50">{!! $type->description !!}</td>
                                <td class="py-3">Rp {{ number_format($type->base_price, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    <p class="text-muted">{{ $type->updated_at->format('d M Y | H:i') }}</p>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        {{-- <a href="{{ route('admin.free-consultation.types.show', $type->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" style="border-color: #00617a; color: #00617a;" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a> --}}
                                        <a href="{{ route('admin.free-consultation.types.edit', $type->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color: #f4b704; color: #f4b704;" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.free-consultation.types.destroy', $type->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(type, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: #cb2786; color: #cb2786;" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-calendar-times me-2"></i>Tidak ada layanan yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $consultationType->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(type, form) {
        event.preventDefault();
        Swal.fire({
            title: "Yakin ingin menghapus layanan ini?",
            text: "Data layanan ini akan dihapus secara permanen dan tidak dapat dipulihkan.",
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