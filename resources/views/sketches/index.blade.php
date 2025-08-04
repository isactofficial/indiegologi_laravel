@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #cb2786;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(203, 39, 134, 0.1);">
                        <i class="fas fa-palette fs-2" style="color: #cb2786;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #cb2786;">Manajemen Sketsa</h2>
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
                                <td class="py-3">
                                    {{-- Menyesuaikan pemanggilan gambar ke field thumbnail --}}
                                    @if($sketch->thumbnail)
                                        <img src="{{ asset('storage/' . $sketch->thumbnail) }}" alt="{{ $sketch->title }}" class="rounded-3 object-fit-cover shadow-sm" style="width: 150px; height: 100px; border: 1px solid #eee;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex justify-content-center align-items-center shadow-sm" style="width: 150px; height: 100px; border: 1px dashed #ccc;">
                                            <span class="text-muted small">Tanpa Gambar</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-semibold text-break">{{ $sketch->title }}</td>
                                <td class="py-3">{{ $sketch->author }}</td>
                                <td class="py-3">
                                    {{-- Menampilkan status sketsa --}}
                                    @php
                                        $statusClass = $sketch->status == 'Published' ? 'badge-status-published' : 'badge-status-draft';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $sketch->status }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        {{-- Menggunakan slug untuk rute --}}
                                        <a href="{{ route('admin.sketches.show', $sketch->slug) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" style="border-color: #00617a; color: #00617a;" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.sketches.edit', $sketch->slug) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color: #f4b704; color: #f4b704;" title="Edit Sketsa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.sketches.destroy', $sketch->slug) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: #cb2786; color: #cb2786;" title="Hapus Sketsa">
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

            {{-- Pagination --}}
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
