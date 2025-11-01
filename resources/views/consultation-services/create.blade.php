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
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #0C2C5A;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-handshake fs-2" style="color: #0C2C5A;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Tambah Layanan Baru</h2>
                        <p class="text-muted mb-0">Buat layanan konsultasi baru untuk ditawarkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.consultation-services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- Bagian Form Utama (Judul, Slug, Harga, dll) --}}
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label text-secondary fw-medium">Judul Layanan</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="slug" class="form-label text-secondary fw-medium">Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" required>
                        @error('slug')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label text-secondary fw-medium">Harga Dasar</label>
                        <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', 0) }}" required min="0">
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="hourly_price" class="form-label text-secondary fw-medium">Harga Per Jam (Tambahan)</label>
                        <input type="number" id="hourly_price" name="hourly_price" class="form-control @error('hourly_price') is-invalid @enderror" value="{{ old('hourly_price', 0) }}" min="0">
                        @error('hourly_price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="base_duration" class="form-label text-secondary fw-medium">Durasi Dasar (jam)</label>
                        <input type="number" id="base_duration" name="base_duration" class="form-control @error('base_duration') is-invalid @enderror" value="{{ old('base_duration', 1) }}" required min="1">
                        @error('base_duration')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label text-secondary fw-medium">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="special" {{ old('status') == 'special' ? 'selected' : '' }}>Special</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="short_description" class="form-label text-secondary fw-medium">Deskripsi Singkat</label>
                        <input type="text" id="short_description" name="short_description" class="form-control @error('short_description') is-invalid @enderror" value="{{ old('short_description') }}">
                        @error('short_description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="thumbnail" class="form-label text-secondary fw-medium">Gambar Thumbnail (Opsional)</label>
                        <div class="position-relative border rounded-3 d-flex align-items-center justify-content-center" style="height: 240px;">
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="position-absolute w-100 h-100 opacity-0" style="cursor: pointer; z-index: 3;">
                            <img src="" alt="Thumbnail Preview" class="position-absolute w-100 h-100 preview-image" style="object-fit: cover; border-radius: 0.375rem; z-index: 2; display: none;">
                            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center border border-secondary bg-white rounded-3 default-overlay" style="pointer-events: none; z-index: 1;">
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
                        <textarea id="product_description" name="product_description" class="form-control @error('product_description') is-invalid @enderror" rows="5" required>{{ old('product_description') }}</textarea>
                        @error('product_description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>


                    @php
                        // -- LOGIKA DATA ADD-ON --
                        $oldAddOns = old('add_ons');

                        // Definisikan opsi painting default
                        $paintingOptions = [
                            ['title' => 'Painting: A5 - Water Color', 'price' => 650000],
                            ['title' => 'Painting: A4 - Water Color', 'price' => 900000],
                            ['title' => 'Painting: Digital', 'price' => 500000],
                        ];

                        // Variabel untuk melacak indeks item yang di-render, untuk JS
                        $renderedItemCount = 0;
                    @endphp


                    {{-- =================================== --}}
                    {{--      BAGIAN ADD-ON LAYANAN LAIN     --}}
                    {{-- =================================== --}}
                    <div class="col-12 mb-4">
                        <h5 class="text-secondary fw-medium mb-3 pt-3 border-top">Opsional: Add-on Layanan Lain</h5>
                        <div id="service-add-on-container" class="vstack gap-3">

                            @if($oldAddOns)
                                {{-- 1A. Render data lama (HANYA TIPE 'existing' ATAU 'custom' NON-PAINTING) --}}
                                @foreach($oldAddOns as $index => $addOn)
                                    @php
                                        $isCustom = $addOn['type'] == 'custom';
                                        $isPainting = $isCustom && isset($addOn['title']) && strpos($addOn['title'], 'Painting:') === 0;
                                    @endphp

                                    @if(!$isPainting) {{-- Filter: Hanya tampilkan yang BUKAN painting --}}
                                        @php $renderedItemCount++; @endphp
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

                                                    {{-- Fields for 'custom' type --}}
                                                    <div class="col-md-5 add-on-custom-fields" @if(!$isCustom) style="display: none;" @endif>
                                                        <label class="form-label text-secondary fw-medium">Nama Add-on</label>
                                                        <input type="text" name="add_ons[{{ $index }}][title]" class="form-control form-control-sm" placeholder="Nama add-on kustom" value="{{ $addOn['title'] ?? '' }}" @if(!$isCustom) disabled @endif>
                                                    </div>
                                                    <div class="col-md-3 add-on-custom-fields" @if(!$isCustom) style="display: none;" @endif>
                                                        <label class="form-label text-secondary fw-medium">Harga Add-on</label>
                                                        <input type="number" name="add_ons[{{ $index }}][price]" class="form-control form-control-sm" placeholder="Harga" value="{{ $addOn['price'] ?? '' }}" @if(!$isCustom) disabled @endif>
                                                    </div>

                                                    {{-- Fields for 'existing' type --}}
                                                    <div class="col-md-8 add-on-existing-fields" @if($isCustom) style="display: none;" @endif>
                                                        <label class="form-label text-secondary fw-medium">Pilih Layanan</label>
                                                        <select name="add_ons[{{ $index }}][service_id]" class="form-select form-select-sm" @if($isCustom) disabled @endif>
                                                            <option value="">Pilih layanan...</option>
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
                                                @error('add_ons.' . $index . '.title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                                @error('add_ons.' . $index . '.price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                                @error('add_ons.' . $index . '.service_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                {{-- 1B. Render default (HANYA $existingServices) --}}
                                @foreach($existingServices ?? [] as $index => $service)
                                    @php $renderedItemCount++; @endphp
                                    <div class="add-on-item card shadow-sm border-0">
                                        <div class="card-body p-3">
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-3">
                                                    <label class="form-label text-secondary fw-medium">Tipe Add-on</label>
                                                    <select name="add_ons[{{ $index }}][type]" class="form-select form-select-sm add-on-type-select" required>
                                                        <option value="custom">Input Manual</option>
                                                        <option value="existing" selected>Pilih Layanan Lain</option>
                                                    </select>
                                                </div>
                                                {{-- Custom fields (hidden by default) --}}
                                                <div class="col-md-5 add-on-custom-fields" style="display: none;">
                                                    <input type="text" name="add_ons[{{ $index }}][title]" class="form-control form-control-sm" disabled>
                                                </div>
                                                <div class="col-md-3 add-on-custom-fields" style="display: none;">
                                                    <input type="number" name="add_ons[{{ $index }}][price]" class="form-control form-control-sm" disabled>
                                                </div>
                                                {{-- Existing fields (visible by default) --}}
                                                <div class="col-md-8 add-on-existing-fields">
                                                    <label class="form-label text-secondary fw-medium">Pilih Layanan</label>
                                                    <select name="add_ons[{{ $index }}][service_id]" class="form-select form-select-sm" required>
                                                        <option value="">Pilih layanan...</option>
                                                        @foreach($existingServices ?? [] as $optionService)
                                                            <option value="{{ $optionService->id }}" @if($optionService->id == $service->id) selected @endif>
                                                                {{ $optionService->title }} (Rp {{ number_format($optionService->price, 0, ',', '.') }})
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
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-service-add-on-btn" class="btn btn-outline-primary mt-3 px-3 py-2">
                            <i class="fas fa-plus me-2"></i>Tambah Add-on Layanan
                        </button>
                    </div>

                    {{-- =================================== --}}
                    {{--     BAGIAN ADD-ON PAINTING CHAR     --}}
                    {{-- =================================== --}}
                    <div class="col-12 mb-4">
                        <h5 class="text-secondary fw-medium mb-3 pt-3 border-top">Opsional: Add-on Painting Character</h5>
                        <div id="painting-add-on-container" class="vstack gap-3">

                            @if($oldAddOns)
                                {{-- 2A. Render data lama (HANYA TIPE 'custom' DENGAN JUDUL 'Painting:') --}}
                                @foreach($oldAddOns as $index => $addOn)
                                    @php
                                        $isCustom = $addOn['type'] == 'custom';
                                        $isPainting = $isCustom && isset($addOn['title']) && strpos($addOn['title'], 'Painting:') === 0;
                                    @endphp

                                    @if($isPainting) {{-- Filter: Hanya tampilkan yang painting --}}
                                        @php $renderedItemCount++; @endphp
                                        <div class="add-on-item card shadow-sm border-0">
                                            <div class="card-body p-3">
                                                <div class="row g-2 align-items-end">
                                                    {{-- Input Tipe 'custom' disembunyikan, tapi wajib ada --}}
                                                    <input type="hidden" name="add_ons[{{ $index }}][type]" value="custom">

                                                    <div class="col-md-8">
                                                        <label class="form-label text-secondary fw-medium">Nama Add-on</label>
                                                        <input type="text" name="add_ons[{{ $index }}][title]" class="form-control form-control-sm" value="{{ $addOn['title'] ?? '' }}" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label text-secondary fw-medium">Harga Add-on</label>
                                                        <input type="number" name="add_ons[{{ $index }}][price]" class="form-control form-control-sm" value="{{ $addOn['price'] ?? '' }}" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-sm btn-outline-danger remove-add-on-btn w-100">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @error('add_ons.' . $index . '.title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                                @error('add_ons.' . $index . '.price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                {{-- 2B. Render default (HANYA $paintingOptions) --}}
                                {{-- Indeks dimulai setelah indeks $existingServices --}}
                                @php $paintingStartIndex = count($existingServices ?? []); @endphp
                                @foreach($paintingOptions as $index => $painting)
                                    @php
                                        $itemIndex = $paintingStartIndex + $index;
                                        $renderedItemCount++;
                                    @endphp
                                    <div class="add-on-item card shadow-sm border-0">
                                        <div class="card-body p-3">
                                            <div class="row g-2 align-items-end">
                                                {{-- Input Tipe 'custom' disembunyikan, tapi wajib ada --}}
                                                <input type="hidden" name="add_ons[{{ $itemIndex }}][type]" value="custom">

                                                <div class="col-md-8">
                                                    <label class="form-label text-secondary fw-medium">Nama Add-on</label>
                                                    <input type="text" name="add_ons[{{ $itemIndex }}][title]" class="form-control form-control-sm" value="{{ $painting['title'] }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label text-secondary fw-medium">Harga Add-on</label>
                                                    <input type="number" name="add_ons[{{ $itemIndex }}][price]" class="form-control form-control-sm" value="{{ $painting['price'] }}" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-add-on-btn w-100">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-painting-btn" class="btn btn-outline-success mt-3 px-3 py-2">
                            <i class="fas fa-plus me-2"></i>Tambah Opsi Painting
                        </button>
                    </div>

                </div>
                <div class="d-flex justify-content-start mt-4 form-actions">
                    <button type="submit" class="btn btn-primary px-4 py-2" style="background-color: #0C2C5A;">Simpan Layanan</button>
                    <a href="{{ route('admin.consultation-services.index') }}" class="btn btn-outline-secondary ms-2 px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- =================================== --}}
{{--         TEMPLATE JAVASCRIPT         --}}
{{-- =================================== --}}

{{-- Template untuk "Add-on Layanan Lain" (bisa custom / existing) --}}
<template id="service-add-on-template">
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

                {{-- Fields for 'custom' type --}}
                <div class="col-md-5 add-on-custom-fields">
                    <label class="form-label text-secondary fw-medium">Nama Add-on</label>
                    <input type="text" name="add_ons[INDEX][title]" class="form-control form-control-sm" placeholder="Nama add-on kustom" required>
                </div>
                <div class="col-md-3 add-on-custom-fields">
                    <label class="form-label text-secondary fw-medium">Harga Add-on</label>
                    <input type="number" name="add_ons[INDEX][price]" class="form-control form-control-sm" placeholder="Harga" required>
                </div>

                {{-- Fields for 'existing' type --}}
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

{{-- Template untuk "Add-on Painting" (HANYA custom) --}}
<template id="painting-add-on-template">
    <div class="add-on-item card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                {{-- Input Tipe 'custom' disembunyikan, tapi wajib ada --}}
                <input type="hidden" name="add_ons[INDEX][type]" value="custom">

                <div class="col-md-8">
                    <label class="form-label text-secondary fw-medium">Nama Add-on</label>
                    <input type="text" name="add_ons[INDEX][title]" class="form-control form-control-sm" placeholder="Mis: Painting: A3 - Oil" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary fw-medium">Harga Add-on</label>
                    <input type="number" name="add_ons[INDEX][price]" class="form-control form-control-sm" placeholder="Harga" required>
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
    // === Skrip Pratinjau Thumbnail ===
    const thumbInput = document.getElementById('thumbnail');
    if (thumbInput) {
        thumbInput.addEventListener('change', function (e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    const parent = thumbInput.parentElement;
                    let preview = parent.querySelector('img.preview-image');
                    preview.src = ev.target.result;
                    preview.style.display = 'block';
                    const defaultOverlay = parent.querySelector('.default-overlay');
                    if (defaultOverlay) defaultOverlay.style.display = 'none';
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }

    // === SKRIP ADD-ON ===

    // Ambil data layanan dari Blade
    const existingServices = @json($existingServices ?? []);

    // Tentukan indeks awal berdasarkan jumlah item yang sudah di-render di Blade
    // Ini penting agar tidak ada konflik indeks array
    let addOnIndex = {{ $renderedItemCount }};

    // --- Bagian 1: Logika "Add-on Layanan Lain" ---
    const serviceAddOnContainer = document.getElementById('service-add-on-container');
    const addServiceAddOnButton = document.getElementById('add-service-add-on-btn');
    const serviceAddOnTemplate = document.getElementById('service-add-on-template');

    if (addServiceAddOnButton && serviceAddOnContainer && serviceAddOnTemplate) {
        addServiceAddOnButton.addEventListener('click', function () {
            const templateContent = serviceAddOnTemplate.innerHTML.replace(/INDEX/g, addOnIndex);
            const newItemWrapper = document.createElement('div');
            newItemWrapper.innerHTML = templateContent;
            const newItem = newItemWrapper.firstElementChild;

            // Isi dropdown 'Pilih Layanan'
            const existingSelect = newItem.querySelector('.add-on-existing-fields select');
            if (existingServices.length > 0) {
                existingServices.forEach(service => {
                    const option = document.createElement('option');
                    option.value = service.id;
                    const formattedPrice = new Intl.NumberFormat('id-ID').format(service.price);
                    option.textContent = `${service.title} (Rp ${formattedPrice})`;
                    existingSelect.appendChild(option);
                });
            } else {
                // Nonaktifkan opsi 'Pilih Layanan' jika tidak ada
                const typeSelect = newItem.querySelector('.add-on-type-select');
                const existingOption = typeSelect.querySelector('option[value="existing"]');
                if (existingOption) {
                    existingOption.disabled = true;
                    existingOption.textContent = 'Pilih Layanan (Tidak ada)';
                }
            }
            serviceAddOnContainer.appendChild(newItem);
            addOnIndex++; // Naikkan indeks global
        });
    }

    // --- Bagian 2: Logika "Add-on Painting" ---
    const paintingContainer = document.getElementById('painting-add-on-container');
    const addPaintingButton = document.getElementById('add-painting-btn');
    const paintingTemplate = document.getElementById('painting-add-on-template');

    if (addPaintingButton && paintingContainer && paintingTemplate) {
        addPaintingButton.addEventListener('click', function () {
            const templateContent = paintingTemplate.innerHTML.replace(/INDEX/g, addOnIndex);
            const newItemWrapper = document.createElement('div');
            newItemWrapper.innerHTML = templateContent;

            paintingContainer.appendChild(newItemWrapper.firstElementChild);
            addOnIndex++; // Naikkan indeks global
        });
    }

    // --- Bagian 3: Event Listeners Global (Delegasi) ---
    // Gunakan document agar berfungsi di kedua kontainer
    document.addEventListener('click', function (e) {
        // Logika Tombol HAPUS (berlaku untuk kedua bagian)
        const removeButton = e.target.closest('.remove-add-on-btn');
        if (removeButton) {
            removeButton.closest('.add-on-item').remove();
        }
    });

    document.addEventListener('change', function (e) {
        // Logika ganti TIPE ADD-ON (hanya berlaku di "Add-on Layanan Lain")
        if (e.target.classList.contains('add-on-type-select')) {
            const item = e.target.closest('.add-on-item');
            const customFields = item.querySelectorAll('.add-on-custom-fields');
            const existingFields = item.querySelector('.add-on-existing-fields');

            const customInputs = item.querySelectorAll('.add-on-custom-fields input');
            const existingSelect = item.querySelector('.add-on-existing-fields select');

            if (e.target.value === 'custom') {
                customFields.forEach(field => field.style.display = 'block');
                existingFields.style.display = 'none';
                customInputs.forEach(input => { input.disabled = false; input.required = true; });
                existingSelect.disabled = true; existingSelect.required = false;
            } else { // 'existing'
                customFields.forEach(field => field.style.display = 'none');
                existingFields.style.display = 'block';
                customInputs.forEach(input => { input.disabled = true; input.required = false; });
                existingSelect.disabled = false; existingSelect.required = true;
            }
        }
    });
});
</script>
@endpush
