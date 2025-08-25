@extends('layouts.admin')

@section('content')
{{-- [WARNA DIUBAH] Menyesuaikan seluruh palet warna dengan tema utama --}}
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
    .badge-status-published { /* MEREPRESENTASIKAN STATUS AKTIF */
        background-color: rgba(0, 97, 122, 0.15);
        color: #00617a;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
    }
    .badge-status-draft { /* MEREPRESENTASIKAN STATUS TIDAK AKTIF/HABIS */
        background-color: rgba(203, 39, 134, 0.15);
        color: #cb2786;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 0.5rem;
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
                        <i class="fas fa-tags fs-2" style="color: var(--theme-primary);"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: var(--theme-primary);">Manajemen Referral</h2>
                        <p class="text-muted mb-0">Kelola kode referral untuk promosi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end">
            <a href="{{ route('admin.referral-codes.create') }}" class="btn btn-sporty-primary d-flex align-items-center px-4 py-2">
                <i class="fas fa-plus me-2"></i>
                <span class="fw-semibold">Tambah Kode Referral</span>
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
                            <th class="py-3">Kode</th>
                            <th class="py-3">Diskon</th>
                            <th class="py-3">Maks Penggunaan</th>
                            <th class="py-3">Digunakan</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referralCodes as $code)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="py-3 fw-semibold text-break" style="color: var(--theme-primary);">{{ $code->code }}</td>
                                <td class="py-3">{{ $code->discount_percentage }}%</td>
                                <td class="py-3">{{ $code->max_uses ?? 'Tidak Terbatas' }}</td>
                                <td class="py-3">{{ $code->current_uses }}</td>
                                <td class="py-3">
                                    @php
                                        $status = 'Aktif';
                                        $statusClass = 'badge-status-published';
                                        $now = now();
                                        if ($code->valid_until && $now->isAfter($code->valid_until)) {
                                            $status = 'Kedaluwarsa';
                                            $statusClass = 'badge-status-draft';
                                        } elseif ($code->max_uses && $code->current_uses >= $code->max_uses) {
                                            $status = 'Habis';
                                            $statusClass = 'badge-status-draft';
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $status }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.referral-codes.edit', $code->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="border-color: var(--theme-accent); color: var(--theme-accent);" title="Edit Kode">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.referral-codes.destroy', $code->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, this.parentElement)" class="btn btn-sm btn-outline-danger rounded-pill px-3" style="border-color: var(--theme-danger); color: var(--theme-danger);" title="Hapus Kode">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-box-open me-2"></i>Tidak ada kode referral yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $referralCodes->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: "Yakin ingin menghapus kode ini?",
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
