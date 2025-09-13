@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('admin.testimonials.index') }}" class="btn px-4 py-2"
           style="background-color: #F0F5FF; color: #5B93FF; border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        <div>
            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-warning px-4 py-2 me-2">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimoni ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger px-4 py-2">
                    <i class="fas fa-trash me-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $testimonial->image_url }}" 
                             alt="{{ $testimonial->name }}" 
                             class="rounded-circle me-4"
                             style="width: 100px; height: 100px; object-fit: cover;">
                        <div>
                            <h2 class="mb-1 fw-bold text-dark">{{ $testimonial->name }}</h2>
                            <p class="mb-1 text-muted">{{ $testimonial->age }} tahun</p>
                            <p class="mb-0 text-primary fw-medium">{{ $testimonial->occupation }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-secondary fw-medium mb-3">Testimoni</h5>
                        <div class="bg-light rounded-3 p-4">
                            <blockquote class="mb-0">
                                <p class="fst-italic text-dark" style="line-height: 1.8; font-size: 1.1rem;">
                                    "{{ $testimonial->quote }}"
                                </p>
                            </blockquote>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            Dibuat: {{ $testimonial->created_at->format('d F Y, H:i') }}
                        </small>
                        @if($testimonial->updated_at != $testimonial->created_at)
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-edit me-1"></i>
                                Diperbarui: {{ $testimonial->updated_at->format('d F Y, H:i') }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body p-4">
                    <h5 class="text-secondary fw-medium mb-3">Informasi Status</h5>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Status Publikasi</label>
                        <div>
                            @if($testimonial->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                    <i class="fas fa-check me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">
                                    <i class="fas fa-times me-1"></i>Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Urutan Tampil</label>
                        <div>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2">
                                {{ $testimonial->sort_order }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Total Karakter</label>
                        <div>
                            <span class="text-dark fw-medium">{{ strlen($testimonial->quote) }} karakter</span>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.testimonials.toggle-status', $testimonial) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-{{ $testimonial->is_active ? 'danger' : 'success' }} w-100">
                                <i class="fas fa-{{ $testimonial->is_active ? 'times' : 'check' }} me-2"></i>
                                {{ $testimonial->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Preview Card --}}
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body p-4">
                    <h5 class="text-secondary fw-medium mb-3">Preview Tampilan</h5>
                    <small class="text-muted mb-3 d-block">Begini tampilan testimoni di halaman depan:</small>
                    
                    <div class="border rounded-3 p-3 bg-light">
                        <div class="testimonial-preview" style="background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 40%, transparent 60%); 
                                                             background-image: url('{{ $testimonial->image_url }}'); 
                                                             background-size: cover; 
                                                             background-position: center; 
                                                             height: 200px; 
                                                             border-radius: 8px; 
                                                             display: flex; 
                                                             align-items: flex-end; 
                                                             position: relative;">
                            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; 
                                       background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.2) 40%, transparent 60%); 
                                       border-radius: 8px;"></div>
                            <div class="text-white p-3" style="position: relative; z-index: 2;">
                                <p class="mb-2 small fst-italic" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    "{{ Str::limit($testimonial->quote, 120) }}"
                                </p>
                                <hr style="border-color: rgba(255,255,255,0.3); margin: 0.5rem 0;">
                                <h6 class="mb-0 fw-bold">{{ $testimonial->name }}</h6>
                                <small class="opacity-75">{{ $testimonial->age }} Tahun, {{ $testimonial->occupation }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        color: #000;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    blockquote p {
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .testimonial-preview {
        transition: transform 0.3s ease;
    }

    .testimonial-preview:hover {
        transform: scale(1.02);
    }
</style>
@endpush
@endsection