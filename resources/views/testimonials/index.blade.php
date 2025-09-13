@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-dark">Manajemen Testimoni</h2>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary px-4 py-2">
            <i class="fas fa-plus me-2"></i>Tambah Testimoni
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 fw-semibold text-dark ps-4" style="width: 80px;">Foto</th>
                            <th class="border-0 fw-semibold text-dark">Nama</th>
                            <th class="border-0 fw-semibold text-dark">Usia</th>
                            <th class="border-0 fw-semibold text-dark">Pekerjaan</th>
                            <th class="border-0 fw-semibold text-dark" style="width: 300px;">Testimoni</th>
                            <th class="border-0 fw-semibold text-dark text-center">Status</th>
                            <th class="border-0 fw-semibold text-dark text-center">Urutan</th>
                            <th class="border-0 fw-semibold text-dark text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                            <tr>
                                <td class="ps-4 py-3">
                                    <img src="{{ $testimonial->image_url }}" 
                                         alt="{{ $testimonial->name }}" 
                                         class="rounded-circle"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark">{{ $testimonial->name }}</div>
                                </td>
                                <td class="py-3">
                                    <span class="text-muted">{{ $testimonial->age }} tahun</span>
                                </td>
                                <td class="py-3">
                                    <span class="text-muted">{{ $testimonial->occupation }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="text-muted small" style="line-height: 1.4;">
                                        {{ $testimonial->short_quote }}
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    @if($testimonial->is_active)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                            <i class="fas fa-check me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">
                                            <i class="fas fa-times me-1"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2">
                                        {{ $testimonial->sort_order }}
                                    </span>
                                </td>
                                <td class="py-3 pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.testimonials.show', $testimonial) }}" 
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3" 
                                           title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" 
                                           class="btn btn-sm btn-outline-warning rounded-pill px-3" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.testimonials.toggle-status', $testimonial) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-{{ $testimonial->is_active ? 'danger' : 'success' }} rounded-pill px-3" 
                                                    title="{{ $testimonial->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="fas fa-{{ $testimonial->is_active ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimoni ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-comment-dots fa-3x mb-3 opacity-50"></i>
                                        <h5>Belum ada testimoni</h5>
                                        <p>Mulai tambahkan testimoni pertama Anda</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($testimonials->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $testimonials->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
    .btn-primary {
        background-color: #5932EA;
        border-color: #5932EA;
    }

    .btn-primary:hover {
        background-color: #4920D5;
        border-color: #4920D5;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
</style>
@endpush
@endsection