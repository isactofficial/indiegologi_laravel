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
        color: gray;
    }

    .alert-custom-success {
        background-color: rgba(12, 44, 90, 0.1);
        color: var(--theme-primary);
        border: 1px solid rgba(12, 44, 90, 0.3);
    }

    /* Menyesuaikan warna status seperti file manage.blade.php */
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

    /* Penyesuaian Tampilan Mobile */
    @media (max-width: 767px) {
        /* Sembunyikan header tabel di mobile */
        .table thead {
            display: none;
        }

        /* Ubah perilaku tabel menjadi block-level elements */
        .table, .table tbody, .table tr, .table td {
            display: block;
            width: 100%;
        }

        /* Jadikan setiap baris sebagai card */
        .table tr {
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            padding: 1rem;
        }

        .table tr:last-child {
            margin-bottom: 0;
        }

        /* Hapus border bawaan tabel */
        .table tr[style] {
            border-bottom: none !important;
        }

        /* Atur ulang padding dan border sel */
        .table td {
            padding: 0.5rem 0.25rem;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right;
            border-bottom: 1px solid #f8f9fa;
        }

        .table td:last-child {
            border-bottom: none;
        }

        /* Tambahkan label untuk setiap data sel */
        .table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            text-align: left;
            margin-right: 1rem;
        }

        /* Atur posisi thumbnail di tengah atas */
        .td-thumbnail {
            display: block;
            text-align: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem !important;
            border-bottom: 1px solid #e9ecef !important;
        }

        /* Pastikan gambar thumbnail tidak gepeng */
        .td-thumbnail img, .td-thumbnail .bg-light {
            width: 100% !important;
            height: auto !important; /* Menjaga rasio aspek gambar */
            max-width: 350px;
            margin: 0 auto;
            object-fit: cover;
        }

        /* Hilangkan label untuk thumbnail */
        .td-thumbnail::before {
            content: none;
        }

        /* Atur posisi tombol aksi */
        .td-actions .d-flex {
            justify-content: flex-end;
            width: 100%;
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
                        <i class="fas fa-palette fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Sketsa</h2>
                        <p class="text-muted mb-0">Kelola koleksi sketsa Anda di sini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end">
            <a href="{{ route('admin.sketches.create') }}" class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Tambah Sketsa Baru</span>
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success rounded-3 alert-custom-success mb-4"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="py-3">Thumbnail</th>
                            <th class="py-3">Judul Sketsa</th>
                            <th class="py-3">Penulis</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sketches as $sketch)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="py-3 td-thumbnail">
                                    @if($sketch->thumbnail)
                                        <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" class="rounded-3 object-fit-cover shadow-sm" style="width: 150px; height: 100px; border: 1px solid #eee;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex justify-content-center align-items-center shadow-sm" style="width: 150px; height: 100px; border: 1px dashed #ccc;">
                                            <span class="text-muted small">Tanpa Gambar</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-semibold text-break" style="color: var(--theme-primary);" data-label="Judul Sketsa">{{ $sketch->title }}</td>
                                <td class="py-3" data-label="Penulis">{{ $sketch->author }}</td>
                                <td class="py-3" data-label="Status">
                                    @php
                                        $statusClass = $sketch->status == 'Published' ? 'badge-status-published' : 'badge-status-draft';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $sketch->status }}</span>
                                </td>
                                <td class="py-3 td-actions" data-label="Aksi">
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

            <div class="d-flex justify-content-center mt-4">
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
