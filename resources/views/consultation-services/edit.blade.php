@extends('layouts.admin')

@section('content')
<style>
    @media (max-width: 768px) {
        /* Aksi tombol di mobile */
        .form-actions {
            flex-direction: column;
            gap: 0.5rem;
            align-items: stretch;
        }
        .form-actions .btn {
            width: 100%;
            margin-left: 0 !important;
        }
    }

    /* Style untuk item add-on */
    .add-on-item .form-label {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }
</style>
<div class="container-fluid px-4" style="min-height: 100vh;">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            {{-- [DIKEMBALIKAN] Warna header dikembalikan seperti semula --}}
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #f4b704;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(244, 183, 4, 0.1);">
                        <i class="fas fa-handshake fs-2" style="color: #f4b704;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #f4b704;">Edit Layanan</h2>
                        <p class="text-muted mb-0">Perbarui detail layanan konsultasi ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.consultation-services.update', $consultationService->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label text-secondary fw-medium">Judul Layanan</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $consultationService->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="slug" class="form-label text-secondary fw-medium">Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $consultationService->slug) }}" required>
                        @error('slug')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Layout Harga & Durasi --}}
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label text-secondary fw-medium">Harga Dasar</label>
                        <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $consultationService->price) }}" required min="0">
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="hourly_price" class="form-label text-secondary fw-medium">Harga Per Jam (Tambahan)</label>
                        <input type="number" id="hourly_price" name="hourly_price" class="form-control @error('hourly_price') is-invalid @enderror" value="{{ old('hourly_price', $consultationService->hourly_price) }}" min="0">
                        @error('hourly_price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="base_duration" class="form-label text-secondary fw-medium">Durasi Dasar (jam)</label>
                        <input type="number" id="base_duration" name="base_duration" class="form-control @error('base_duration') is-invalid @enderror" value="{{ old('base_duration', $consultationService->base_duration) }}" required min="1">
                        @error('base_duration')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label text-secondary fw-medium">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status', $consultationService->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $consultationService->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="special" {{ old('status', $consultationService->status) == 'special' ? 'selected' : '' }}>Special</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="short_description" class="form-label text-secondary fw-medium">Deskripsi Singkat</label>
                        <input type="text" id="short_description" name="short_description" class="form-control @error('short_description') is-invalid @enderror" value="{{ old('short_description', $consultationService->short_description) }}">
                        @error('short_description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="thumbnail" class="form-label text-secondary fw-medium">Gambar Thumbnail (Opsional)</label>
                        <div class="position-relative border rounded-3 d-flex align-items-center justify-content-center" style="height: 240px;">
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="position-absolute w-100 h-100 opacity-0" style="cursor: pointer; z-index: 3;">

                            {{-- Preview Image --}}
                            <img src="{{ $consultationService->thumbnail ? asset('storage/' . $consultationService->thumbnail) : '' }}" alt="Current Thumbnail" class="position-absolute w-100 h-100 preview-image" style="object-fit: cover; border-radius: 0.375rem; z-index: 2; @if(!$consultationService->thumbnail) display: none; @endif">

                            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center border border-secondary bg-white rounded-3 default-overlay" style="pointer-events: none; z-index: 1; @if($consultationService->thumbnail) display: none; @endif">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#6c757d" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                                </svg>
                            </div>
                        </div>
                        @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-4">
                        <label for="product_description" class="form-label text-secondary fw-medium">Deskripsi Produk Lengkap</label>
                        <textarea id="product_description" name="product_description" class="form-control @error('product_description') is-invalid @enderror" rows="5" required>{{ old('product_description', $consultationService->product_description) }}</textarea>
                        @error('product_description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- =================================== --}}
                    {{--         BAGIAN ADD-ON BARU          --}}
                    {{-- =================================== --}}
                    <div class="col-12 mb-4">
                        <h5 class="text-secondary fw-medium mb-3 pt-3 border-top">Opsional: Add-on Layanan</h5>

                        {{-- Kontainer untuk add-on dinamis --}}
                        <div id="add-on-container" class="vstack gap-3">

                            {{-- Repopulasi dari validasi error atau data yang ada --}}
                            @php
                                // Prioritaskan old() jika ada error, jika tidak, gunakan data dari database
                                $addOns = old('add_ons', $consultationService->add_ons ?? []);
                            @endphp
                            @if(!empty($addOns))
                                @foreach($addOns as $index => $addOn)
                                    @php
                                        // Pastikan $addOn adalah array
                                        $addOn = (array) $addOn;
                                        $isCustom = $addOn['type'] == 'custom';
                                    @endphp
                                    <div class="add-on-item card shadow-sm border-0">
                                        <div class="card-body p-3">
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-3">
                                                    <label class="form-label text-secondary fw-medium">Tipe Add-on</label>
                                                    <select name="add_ons[{{ $index }}][type]" class="form-select form-select-sm add-on-type-select" required>
                                                        <option value="custom" @if($isCustom) selected @endif>Input Manual</option>
                                                        <option value="existing" @if(!$isCustom) selected @endif>Pilih Layanan Lain</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-5 add-on-custom-fields" @if(!$isCustom) style="display: none;" @endif>
                                                    <label class="form-label text-secondary fw-medium">Nama Add-on</label>
                                                    <input type="text" name="add_ons[{{ $index }}][title]" class="form-control form-control-sm" placeholder="Nama add-on kustom" value="{{ $addOn['title'] ?? '' }}" @if(!$isCustom) disabled @endif>
                                                </div>
                                                <div class="col-md-3 add-on-custom-fields" @if(!$isCustom) style="display: none;" @endif>
                                                    <label class="form-label text-secondary fw-medium">Harga Add-on</label>
                                                    <input type="number" name="add_ons[{{ $index }}][price]" class="form-control form-control-sm" placeholder="Harga" value="{{ $addOn['price'] ?? '' }}" @if(!$isCustom) disabled @endif>
                                                </div>

                                                <div class="col-md-8 add-on-existing-fields" @if($isCustom) style="display: none;" @endif>
                                                    <label class="form-label text-secondary fw-medium">Pilih Layanan</label>
                                                    <select name="add_ons[{{ $index }}][service_id]" class="form-select form-select-sm" @if($isCustom) disabled @endif>
                                                        <option value="">Pilih layanan...</option>
                                                        {{-- Loop dari $existingServices yang di-pass dari controller --}}
                                                        @foreach($existingServices ?? [] as $service)
                                                            <option value="{{ $service->id }}" @if(!$isCustom && ($addOn['service_id'] ?? null) == $service->id) selected @endif>
                                                                {{ $service->title }} (Rp {{ number_format($service->price, 0, ',', '.') }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-add-on-btn w-100">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            {{-- Menampilkan error validasi per item --}}
                                            @error('add_ons.' . $index . '.title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            @error('add_ons.' . $index . '.price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            @error('add_ons.' . $index . '.service_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            {{-- Akhir dari repopulasi --}}

                        </div>

                        <button type="button" id="add-add-on-btn" class="btn btn-outline-primary mt-3 px-3 py-2">
                            <i class="fas fa-plus me-2"></i>Tambah Add-on
                        </button>
                    </div>
                    {{-- =================================== --}}
                    {{--       AKHIR BAGIAN ADD-ON         --}}
                    {{-- =================================== --}}

                </div>
                <div class="d-flex justify-content-start mt-4 form-actions">
                    <button type="submit" class="btn btn-success px-4 py-2">Update Layanan</button>
                    <a href="{{ route('admin.consultation-services.index') }}" class="btn btn-outline-secondary ms-2 px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Template untuk item add-on baru (digunakan oleh JS) --}}
<template id="add-on-template">
    <div class="add-on-item card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-secondary fw-medium">Tipe Add-on</label>
                    <select name="add_ons[INDEX][type]" class="form-select form-select-sm add-on-type-select" required>
                        <option value="custom" selected>Input Manual</option>
                        <option value="existing">Pilih Layanan Lain</option>
                    </select>
                </div>

                <div class="col-md-5 add-on-custom-fields">
                    <label class="form-label text-secondary fw-medium">Nama Add-on</label>
                    <input type="text" name="add_ons[INDEX][title]" class="form-control form-control-sm" placeholder="Nama add-on kustom" required>
                </div>
                <div class="col-md-3 add-on-custom-fields">
                    <label class="form-label text-secondary fw-medium">Harga Add-on</label>
                    <input type="number" name="add_ons[INDEX][price]" class="form-control form-control-sm" placeholder="Harga" required>
                </div>

                <div class="col-md-8 add-on-existing-fields" style="display: none;">
                    <label class="form-label text-secondary fw-medium">Pilih Layanan</label>
                    <select name="add_ons[INDEX][service_id]" class="form-select form-select-sm" disabled required>
                        <option value="">Pilih layanan...</option>
                        {{-- Opsi akan ditambahkan oleh JavaScript --}}
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-add-on-btn w-100">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // === Skrip Pratinjau Thumbnail (Sudah Ada, sedikit dimodifikasi) ===
    const thumbInput = document.getElementById('thumbnail');
    if (thumbInput) {
        thumbInput.addEventListener('change', function (e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    const parent = thumbInput.parentElement;

                    let preview = parent.querySelector('img.preview-image');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.classList.add('position-absolute', 'w-100', 'h-100', 'preview-image');
                        preview.style.objectFit = 'cover';
                        preview.style.borderRadius = '0.375rem';
                        preview.style.zIndex = '2';
                        parent.appendChild(preview);
                    }
                    preview.src = ev.target.result;
                    preview.style.display = 'block';

                    const defaultOverlay = parent.querySelector('.default-overlay');
                    if (defaultOverlay) defaultOverlay.style.display = 'none';
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }

    // === SKRIP ADD-ON BARU ===

    // Ambil data layanan dari Blade
    const existingServices = @json($existingServices ?? []);

    // Ambil data add-ons yang sudah ada
    @php
        $jsAddOns = old('add_ons', $consultationService->add_ons ?? []);
    @endphp
    const existingAddOns = @json($jsAddOns ?? []);

    const addOnContainer = document.getElementById('add-on-container');
    const addAddOnButton = document.getElementById('add-add-on-btn');
    const addOnTemplate = document.getElementById('add-on-template');

    // Tentukan indeks awal berdasarkan item 'old' atau data yang ada
    let addOnIndex = {{ count($jsAddOns) }};

    if (addAddOnButton) {
        addAddOnButton.addEventListener('click', function () {
            // Ganti placeholder 'INDEX' dengan indeks unik
            const templateContent = addOnTemplate.innerHTML.replace(/INDEX/g, addOnIndex);
            const newItemWrapper = document.createElement('div');
            newItemWrapper.innerHTML = templateContent;
            const newItem = newItemWrapper.firstElementChild;

            // Isi dropdown 'Pilih Layanan'
            const existingSelect = newItem.querySelector('.add-on-existing-fields select');
            if (existingServices.length > 0) {
                existingServices.forEach(service => {
                    const option = document.createElement('option');
                    option.value = service.id;
                    // Format harga ke Rupiah
                    const formattedPrice = new Intl.NumberFormat('id-ID').format(service.price);
                    option.textContent = `${service.title} (Rp ${formattedPrice})`;
                    existingSelect.appendChild(option);
                });
            } else {
                // Jika tidak ada layanan lain, nonaktifkan opsi 'Pilih Layanan'
                const typeSelect = newItem.querySelector('.add-on-type-select');
                const existingOption = typeSelect.querySelector('option[value="existing"]');
                if (existingOption) {
                    existingOption.disabled = true;
                    existingOption.textContent = 'Pilih Layanan (Tidak ada)';
                }
            }

            addOnContainer.appendChild(newItem);
            addOnIndex++; // Naikkan indeks untuk item berikutnya
        });
    }

    if (addOnContainer) {
        // Delegasi event untuk tombol HAPUS
        addOnContainer.addEventListener('click', function (e) {
            const removeButton = e.target.closest('.remove-add-on-btn');
            if (removeButton) {
                removeButton.closest('.add-on-item').remove();
            }
        });

        // Delegasi event untuk SELECT TIPE ADD-ON
        addOnContainer.addEventListener('change', function (e) {
            if (e.target.classList.contains('add-on-type-select')) {
                const item = e.target.closest('.add-on-item');
                const customFields = item.querySelectorAll('.add-on-custom-fields');
                const existingFields = item.querySelector('.add-on-existing-fields');

                const customInputs = item.querySelectorAll('.add-on-custom-fields input');
                const existingSelect = item.querySelector('.add-on-existing-fields select');

                if (e.target.value === 'custom') {
                    // Tampilkan input manual
                    customFields.forEach(field => field.style.display = 'block');
                    existingFields.style.display = 'none';
                    // Aktifkan input manual, nonaktifkan select
                    customInputs.forEach(input => {
                        input.disabled = false;
                        input.required = true;
                    });
                    existingSelect.disabled = true;
                    existingSelect.required = false;

                } else { // e.target.value === 'existing'
                    // Tampilkan select layanan
                    customFields.forEach(field => field.style.display = 'none');
                    existingFields.style.display = 'block';
                    // Nonaktifkan input manual, aktifkan select
                    customInputs.forEach(input => {
                        input.disabled = true;
                        input.required = false;
                    });
                    existingSelect.disabled = false;
                    existingSelect.required = true;
                }
            }
        });
    }
});
</script>
@endpush
