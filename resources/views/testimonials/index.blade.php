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

    /* Status badges sesuai dengan sketsa */
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

    .badge-order {
        background-color: rgba(244, 183, 4, 0.15);
        color: #b8860b;
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

    /* Mobile Responsive Styles - MENIRU manage.blade.php */
    .mobile-testimonial-cards {
        display: none; /* Sembunyikan di desktop */
    }

    @media (max-width: 768px) {
        /* DIUBAH: Mengurangi padding container luar agar kartu di dalam lebih lebar */
        .card-body {
            padding: 1rem !important;
        }

        .table-responsive {
            display: none; /* Sembunyikan tabel di mobile */
        }
        .mobile-testimonial-cards {
            display: block; /* Tampilkan kartu di mobile */
        }
        .testimonial-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;      /* DIUBAH: Menyamakan padding dalam dengan artikel */
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #f0f0f0;
        }
        
        .mobile-testimonial-cards .testimonial-card:last-child {
            margin-bottom: 0;
        }

        .testimonial-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .testimonial-header img {
            width: 50px; /* Disesuaikan agar proporsional */
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--theme-primary);
        }
        .testimonial-header-info h5 {
            font-size: 1.1rem; /* Disesuaikan agar proporsional */
            font-weight: 700;
            color: var(--theme-primary);
            margin-bottom: 0.25rem;
        }
        .testimonial-header-info p {
            font-size: 0.9rem; /* Disesuaikan agar proporsional */
            color: #6c757d;
            margin-bottom: 0;
        }
        .testimonial-quote {
            font-style: italic;
            color: #343a40;
            margin-bottom: 1.25rem;
            padding-left: 1rem;
            border-left: 3px solid #f0f4f8;
            font-size: 1rem;
        }
        .testimonial-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }
        .testimonial-actions {
            display: flex;
            gap: 0.5rem;
        }
        .testimonial-actions .btn, .testimonial-actions form {
            flex: 1;
        }
        .testimonial-actions .btn {
            width: 100%;
            padding: 0.5rem;
            border-radius: 0.5rem;
        }
    }

    @media (max-width: 576px) {
        /* DIUBAH: Menyesuaikan padding di layar lebih kecil, sama seperti artikel */
        .card-body {
            padding: 0.75rem !important;
        }
        .testimonial-card {
            padding: 0.75rem;
        }
        .testimonial-header-info h5 {
            font-size: 1rem;
        }
        .testimonial-header-info p {
             font-size: 0.85rem;
        }
        .testimonial-quote {
            font-size: 0.95rem;
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
                        <i class="fas fa-comment-dots fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Testimoni</h2>
                        <p class="text-muted mb-0">Kelola testimoni pelanggan Anda di sini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end">
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Tambah Testimoni</span>
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

            {{-- [DESKTOP] Tampilan Tabel --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="py-3">Foto</th>
                            <th class="py-3">Nama</th>
                            <th class="py-3">Usia</th>
                            <th class="py-3">Pekerjaan</th>
                            <th class="py-3">Lokasi</th>
                            <th class="py-3">Testimoni</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Urutan</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="py-3">
                                    <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->name }}" class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #eee;">
                                </td>
                                <td class="py-3 fw-semibold text-break" style="color: var(--theme-primary);">{{ $testimonial->name }}</td>
                                <td class="py-3">{{ $testimonial->age }} tahun</td>
                                <td class="py-3">{{ $testimonial->occupation }}</td>
                                <td class="py-3">{{ $testimonial->location ?? '-' }}</td>
                                <td class="py-3">
                                    <div class="text-muted small" style="line-height: 1.4; max-width: 300px;">{{ $testimonial->short_quote }}</div>
                                </td>
                                <td class="py-3">
                                    @php
                                        $statusClass = $testimonial->is_active ? 'badge-status-active' : 'badge-status-inactive';
                                        $statusText = $testimonial->is_active ? 'Aktif' : 'Nonaktif';
                                        $statusIcon = $testimonial->is_active ? 'fa-check' : 'fa-times';
                                    @endphp
                                    <span class="badge {{ $statusClass }}"><i class="fas {{ $statusIcon }} me-1"></i>{{ $statusText }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="badge badge-order">{{ $testimonial->sort_order }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" style="border-color: var(--theme-primary); color: var(--theme-primary);" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color: var(--theme-accent); color: var(--theme-accent);" title="Edit Testimoni"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.testimonials.toggle-status', $testimonial) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $testimonial->is_active ? 'danger' : 'success' }} rounded-pill px-3" style="border-color: var(--theme-{{ $testimonial->is_active ? 'danger' : 'primary' }}); color: var(--theme-{{ $testimonial->is_active ? 'danger' : 'primary' }});" title="{{ $testimonial->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"><i class="fas fa-{{ $testimonial->is_active ? 'times' : 'check' }}"></i></button>
                                        </form>
                                        <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: var(--theme-danger); color: var(--theme-danger);" title="Hapus Testimoni"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center py-4 text-muted"><i class="fas fa-comment-dots me-2"></i>Tidak ada testimoni yang ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- [MOBILE] Tampilan Kartu --}}
            <div class="mobile-testimonial-cards">
                @forelse($testimonials as $testimonial)
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->name }}">
                            <div class="testimonial-header-info">
                                <h5>{{ $testimonial->name }}</h5>
                                <p>{{ $testimonial->occupation }} @if($testimonial->location) &bull; {{ $testimonial->location }} @endif &bull; {{ $testimonial->age }} tahun</p>
                            </div>
                        </div>

                        <p class="testimonial-quote">"{{ $testimonial->short_quote }}"</p>

                        <div class="testimonial-meta">
                            <div>
                                @php
                                    $statusClass = $testimonial->is_active ? 'badge-status-active' : 'badge-status-inactive';
                                    $statusText = $testimonial->is_active ? 'Aktif' : 'Nonaktif';
                                    $statusIcon = $testimonial->is_active ? 'fa-check' : 'fa-times';
                                @endphp
                                <span class="badge {{ $statusClass }}"><i class="fas {{ $statusIcon }} me-1"></i>{{ $statusText }}</span>
                            </div>
                            <div>
                                <span class="badge badge-order">Urutan: {{ $testimonial->sort_order }}</span>
                            </div>
                        </div>

                        <div class="testimonial-actions">
                            <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-sm btn-outline-info" style="border-color: var(--theme-primary); color: var(--theme-primary);" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-sm btn-outline-secondary" style="border-color: var(--theme-accent); color: var(--theme-accent);" title="Edit Testimoni"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger" style="border-color: var(--theme-danger); color: var(--theme-danger);" title="Hapus Testimoni"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted"><i class="fas fa-comment-dots me-2"></i>Tidak ada testimoni yang ditemukan.</div>
                @endforelse
            </div>

            @if($testimonials->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $testimonials->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: "Yakin ingin menghapus testimoni ini?",
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