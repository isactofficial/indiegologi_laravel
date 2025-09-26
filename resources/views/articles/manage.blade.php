@extends('layouts.admin')

@section('content')
<style>
    :root {
        --theme-primary: #0C2C5A;
        --theme-accent: #f4b704;
        --theme-danger: #cb2786;
    }

    /* Custom Select Dropdown */
    .custom-select-dropdown {
        background-color: #f0f4f8;
        border-radius: 0.75rem;
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--theme-primary);
        transition: all 0.3s ease-in-out;
        border: 1px solid rgba(12, 44, 90, 0.2);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .custom-select-dropdown:hover {
        background-color: #e6eef7;
    }

    .custom-select-dropdown:focus {
        border-color: var(--theme-primary);
        box-shadow: 0 0 0 0.25rem rgba(12, 44, 90, 0.25);
        outline: none;
    }

    .custom-select-dropdown option {
        font-weight: normal;
        color: #495057;
    }

    /* General button styling */
    .btn-theme-primary {
        background-color: var(--theme-primary);
        border-color: var(--theme-primary);
        color: white;
        border-radius: 0.75rem;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 8px rgba(12, 44, 90, 0.2);
    }

    .btn-theme-primary:hover {
        background-color: #081f3f;
        border-color: #081f3f;
        transform: translateY(-2px);
        color: white;
    }

    .btn-theme-accent {
        background-color: var(--theme-accent);
        border-color: var(--theme-accent);
        color: #333;
        border-radius: 0.75rem;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 8px rgba(244, 183, 4, 0.2);
    }

    .btn-theme-accent:hover {
        background-color: #e0a700;
        border-color: #e0a700;
        transform: translateY(-2px);
    }

    .btn-theme-danger {
        background-color: var(--theme-danger);
        border-color: var(--theme-danger);
        color: white;
        border-radius: 0.75rem;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 8px rgba(203, 39, 134, 0.2);
    }

    .btn-theme-danger:hover {
        background-color: #a7206f;
        border-color: #a7206f;
        transform: translateY(-2px);
    }

    /* Table styling */
    .table th, .table td {
        padding: 1rem;
        border-color: #e9ecef;
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        color: #6c757d !important;
    }

    /* Alert styling */
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

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.5rem !important;
        }

        /* Header adjustments */
        .article-header {
            padding: 1rem !important;
            margin-bottom: 1rem !important;
        }

        .article-header .d-flex {
            flex-direction: column !important;
            align-items: flex-start !important;
            text-align: center;
        }

        .article-header .rounded-circle {
            width: 50px !important;
            height: 50px !important;
            margin-bottom: 1rem !important;
            margin-right: 0 !important;
            align-self: center;
        }

        .article-header h2 {
            font-size: 1.5rem !important;
            text-align: center;
            width: 100%;
        }

        .article-header p {
            font-size: 0.9rem !important;
            text-align: center;
            width: 100%;
        }

        /* Button adjustments */
        .add-button-container {
            margin-bottom: 1rem !important;
        }

        .btn-theme-primary {
            width: 100%;
            justify-content: center !important;
            padding: 0.75rem 1rem !important;
        }

        /* Card adjustments */
        .card-body {
            padding: 1rem !important;
        }

        /* Sort dropdown adjustments */
        .sort-container {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.5rem !important;
        }

        .sort-container h1 {
            font-size: 1.25rem !important;
            margin-bottom: 0.5rem !important;
        }

        .sort-form {
            width: 100%;
        }

        .sort-form .d-flex {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }

        .custom-select-dropdown {
            width: 100% !important;
        }

        /* Table becomes cards on mobile */
        .table-responsive {
            display: none;
        }

        .mobile-article-cards {
            display: block;
        }

        .article-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #f0f0f0;
        }

        .article-thumbnail {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .article-thumbnail-placeholder {
            width: 100%;
            height: 150px;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            border: 2px dashed #dee2e6;
        }

        .article-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--theme-primary);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .article-views {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .article-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .article-actions .btn {
            flex: 1;
            min-width: 45px;
            padding: 0.5rem;
            border-radius: 0.5rem;
        }

        /* Pagination adjustments */
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.25rem;
        }

        .page-link {
            padding: 0.375rem 0.5rem !important;
            font-size: 0.875rem;
        }
    }

    /* Desktop view */
    @media (min-width: 769px) {
        .mobile-article-cards {
            display: none;
        }
    }

    /* Extra small devices */
    @media (max-width: 576px) {
        .container-fluid {
            padding: 0.25rem !important;
        }

        .article-header {
            padding: 0.75rem !important;
        }

        .article-header h2 {
            font-size: 1.25rem !important;
        }

        .article-header p {
            font-size: 0.85rem !important;
        }

        .card-body {
            padding: 0.75rem !important;
        }

        .article-card {
            padding: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .article-title {
            font-size: 0.95rem;
        }

        .article-actions {
            gap: 0.25rem;
        }

        .article-actions .btn {
            min-width: 40px;
            padding: 0.375rem;
        }
    }
</style>

<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Article Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="article-header bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid var(--theme-primary);">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-newspaper fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Artikel</h2>
                        <p class="text-muted mb-0">Kelola publikasi, draf, dan pantau pertumbuhan artikel Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 add-button-container d-flex justify-content-end">
            <a href="{{ route('admin.articles.create') }}" class="btn btn-theme-primary d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Tambah Artikel Baru</span>
            </a>
        </div>
    </div>

    {{-- Article Table/Cards --}}
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success rounded-3 alert-custom-success mb-4"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif

            <div class="sort-container d-flex justify-content-between align-items-center mb-4">
                <h1 class="fs-5 fw-bold" style="color: var(--theme-primary);">Daftar Artikel</h1>
                <div class="sort-form">
                    <form method="GET" class="d-flex align-items-center gap-2">
                        <span class="text-muted fw-semibold me-2 d-none d-md-inline">Urutkan:</span>
                        <select name="sort" class="form-select form-select-sm custom-select-dropdown border-0" onchange="this.form.submit()" style="width: auto;">
                            <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Tanggal Terbaru</option>
                            <option value="view" {{ request('sort') == 'view' ? 'selected' : '' }}>Jumlah Dilihat</option>
                            <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status Publikasi</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Desktop Table View --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="py-3">Thumbnail</th>
                            <th class="py-3">Judul Artikel</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Dilihat</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="py-3">
                                    @if($article->thumbnail)
                                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="thumbnail" class="rounded-3 object-fit-cover shadow-sm" style="width: 200px; height: 100px; border: 1px solid #eee;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex justify-content-center align-items-center shadow-sm" style="width: 200px; height: 100px; border: 1px dashed #ccc;">
                                            <span class="text-muted small">Tanpa Gambar</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-semibold text-break" style="color: var(--theme-primary);">{{ $article->title }}</td>
                                <td class="py-3">
                                    @php
                                        $statusClass = ($article->status == 'Published') ? 'badge-status-published' : 'badge-status-draft';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ $article->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-center"><i class="fas fa-eye me-1 text-muted"></i>{{ $article->views }}</td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.articles.show', $article->slug) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" style="border-color: var(--theme-primary); color: var(--theme-primary);" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.articles.edit', $article->slug) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color: var(--theme-accent); color: var(--theme-accent);" title="Edit Artikel">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.articles.destroy', $article->slug) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: var(--theme-danger); color: var(--theme-danger);" title="Hapus Artikel">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-box-open me-2"></i>Tidak ada artikel yang ditemukan. Mulai tulis artikel baru!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards View --}}
            <div class="mobile-article-cards">
                @forelse($articles as $article)
                    <div class="article-card">
                        @if($article->thumbnail)
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="thumbnail" class="article-thumbnail">
                        @else
                            <div class="article-thumbnail-placeholder">
                                <span class="text-muted">Tanpa Gambar</span>
                            </div>
                        @endif
                        
                        <div class="article-title">{{ $article->title }}</div>
                        
                        <div class="article-meta">
                            <div>
                                @php
                                    $statusClass = ($article->status == 'Published') ? 'badge-status-published' : 'badge-status-draft';
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ $article->status }}
                                </span>
                            </div>
                            <div class="article-views">
                                <i class="fas fa-eye me-1"></i>{{ $article->views }}
                            </div>
                        </div>
                        
                        <div class="article-actions">
                            <a href="{{ route('admin.articles.show', $article->slug) }}" class="btn btn-sm btn-outline-info" style="border-color: var(--theme-primary); color: var(--theme-primary);" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.articles.edit', $article->slug) }}" class="btn btn-sm btn-outline-secondary" style="border-color: var(--theme-accent); color: var(--theme-accent);" title="Edit Artikel">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.articles.destroy', $article->slug) }}" method="POST" class="d-inline" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger w-100" style="border-color: var(--theme-danger); color: var(--theme-danger);" title="Hapus Artikel">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-box-open me-2"></i>Tidak ada artikel yang ditemukan. Mulai tulis artikel baru!
                    </div>
                @endforelse
            </div>

            {{-- Custom Pagination --}}
            @if($articles->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    <nav aria-label="Article pagination">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($articles->onFirstPage())
                                <li class="page-item disabled"><span class="page-link rounded-pill border-0" style="color: #ccc;">&laquo;</span></li>
                            @else
                                <li class="page-item">
                                    <a class="page-link rounded-pill border-0" href="{{ $articles->previousPageUrl() }}" rel="prev" style="color: var(--theme-primary); background-color: #e6eef7;">&laquo;</a>
                                </li>
                            @endif

                            {{-- Page Number Links --}}
                            @php
                                $currentPage = $articles->currentPage();
                                $lastPage = $articles->lastPage();
                                $pageRange = 5;
                                $startPage = max(1, $currentPage - floor($pageRange / 2));
                                $endPage = min($lastPage, $currentPage + floor($pageRange / 2));
                                if ($currentPage < floor($pageRange / 2)) { $endPage = min($lastPage, $pageRange); }
                                if ($currentPage > $lastPage - floor($pageRange / 2)) { $startPage = max(1, $lastPage - $pageRange + 1); }
                            @endphp

                            @for ($i = $startPage; $i <= $endPage; $i++)
                                @if ($i == $currentPage)
                                    <li class="page-item active">
                                        <span class="page-link rounded-pill border-0" style="background-color: var(--theme-primary); border-color: var(--theme-primary); color: white; font-weight: bold;">{{ $i }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link rounded-pill border-0" href="{{ $articles->url($i) }}" style="color: var(--theme-primary); background-color: #e6eef7;">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor

                            {{-- Next Page Link --}}
                            @if ($articles->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link rounded-pill border-0" href="{{ $articles->nextPageUrl() }}" rel="next" style="color: var(--theme-primary); background-color: #e6eef7;">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled"><span class="page-link rounded-pill border-0" style="color: #ccc;">&raquo;</span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: "Yakin ingin menghapus artikel ini?",
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

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: "{{ session('success') }}",
        text: "Operasi berhasil!",
        showConfirmButton: false,
        timer: 2000,
        customClass: { popup: 'rounded-4' }
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: "Oops...",
        text: "{{ session('error') }}",
        confirmButtonColor: "#cb2786",
        customClass: {
            popup: 'rounded-4',
            confirmButton: 'rounded-pill px-4'
        }
    });
    @endif
</script>
@endsection