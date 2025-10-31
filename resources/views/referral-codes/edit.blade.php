@extends('layouts.admin')

@push('styles')
{{-- [WARNA DIUBAH] Menyesuaikan style form dan tombol --}}
<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #0C2C5A;
        box-shadow: 0 0 0 0.25rem rgba(12, 44, 90, 0.25);
    }

    .btn-success {
        background-color: #0C2C5A;
        border-color: #0C2C5A;
    }

    .btn-success:hover {
        background-color: #081f3f;
        border-color: #081f3f;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #0C2C5A;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                        style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                        <i class="fas fa-tags fs-2" style="color: #0C2C5A;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Edit Kode Referral</h2>
                        <p class="text-muted mb-0">Perbarui detail kode referral.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.referral-codes.update', $referralCode->id) }}" method="POST" id="referralForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label text-secondary fw-medium">Kode Referral</label>
                        <input type="text" id="code" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $referralCode->code) }}" required>
                        @error('code')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="discount_type" class="form-label text-secondary fw-medium">Tipe Diskon</label>
                        <select id="discount_type" name="discount_type" class="form-select @error('discount_type') is-invalid @enderror" required>
                            <option value="percentage" {{ old('discount_type', $referralCode->discount_type) == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed" {{ old('discount_type', $referralCode->discount_type) == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                        </select>
                        @error('discount_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Field untuk diskon persentase --}}
                    <div class="col-md-6 mb-3 discount-percentage-field">
                        <label for="discount_percentage" class="form-label text-secondary fw-medium">Persentase Diskon (%)</label>
                        <input type="number" id="discount_percentage" name="discount_percentage" class="form-control @error('discount_percentage') is-invalid @enderror"
                            value="{{ old('discount_percentage', $referralCode->discount_percentage) }}" min="0" max="100" step="0.01">
                        @error('discount_percentage')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Field untuk diskon nominal --}}
                    <div class="col-md-6 mb-3 discount-amount-field" style="display: none;">
                        <label for="discount_amount" class="form-label text-secondary fw-medium">Nominal Diskon (Rp)</label>
                        <input type="number" id="discount_amount" name="discount_amount" class="form-control @error('discount_amount') is-invalid @enderror"
                            value="{{ old('discount_amount', $referralCode->discount_amount) }}" min="0" step="1000">
                        @error('discount_amount')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Field minimum purchase --}}
                    <div class="col-md-6 mb-3">
                        <label for="min_purchase_amount" class="form-label text-secondary fw-medium">Minimum Pembelian (Opsional)</label>
                        <input type="number" id="min_purchase_amount" name="min_purchase_amount" class="form-control @error('min_purchase_amount') is-invalid @enderror"
                            value="{{ old('min_purchase_amount', $referralCode->min_purchase_amount) }}" min="0" step="1000">
                        @error('min_purchase_amount')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Field status aktif --}}
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $referralCode->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Kode Aktif</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="max_uses" class="form-label text-secondary fw-medium">Maksimal Penggunaan (Opsional)</label>
                        <input type="number" id="max_uses" name="max_uses" class="form-control @error('max_uses') is-invalid @enderror" value="{{ old('max_uses', $referralCode->max_uses) }}">
                        @error('max_uses')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="current_uses" class="form-label text-secondary fw-medium">Penggunaan Saat Ini</label>
                        <input type="number" id="current_uses" name="current_uses" class="form-control" value="{{ $referralCode->current_uses }}" readonly disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="valid_from" class="form-label text-secondary fw-medium">Tanggal Mulai Berlaku (Opsional)</label>
                        <input type="date" id="valid_from" name="valid_from" class="form-control @error('valid_from') is-invalid @enderror" value="{{ old('valid_from', $referralCode->valid_from ? $referralCode->valid_from->format('Y-m-d') : '') }}">
                        @error('valid_from')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="valid_until" class="form-label text-secondary fw-medium">Tanggal Kedaluwarsa (Opsional)</label>
                        <input type="date" id="valid_until" name="valid_until" class="form-control @error('valid_until') is-invalid @enderror" value="{{ old('valid_until', $referralCode->valid_until ? $referralCode->valid_until->format('Y-m-d') : '') }}">
                        @error('valid_until')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-start mt-4">
                    <button type="submit" class="btn btn-success px-4 py-2">Update Kode</button>
                    <a href="{{ route('admin.referral-codes.index') }}" class="btn btn-outline-secondary ms-2 px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const discountType = document.getElementById('discount_type');
        const percentageField = document.querySelector('.discount-percentage-field');
        const amountField = document.querySelector('.discount-amount-field');
        const discountPercentage = document.getElementById('discount_percentage');
        const discountAmount = document.getElementById('discount_amount');

        function toggleDiscountFields() {
            if (discountType.value === 'percentage') {
                percentageField.style.display = 'block';
                amountField.style.display = 'none';
                // Set required attributes
                discountPercentage.required = true;
                discountAmount.required = false;
            } else {
                percentageField.style.display = 'none';
                amountField.style.display = 'block';
                // Set required attributes
                discountPercentage.required = false;
                discountAmount.required = true;
            }
        }

        // Handle form submission
        document.getElementById('referralForm').addEventListener('submit', function(e) {
            if (discountType.value === 'percentage' && !discountPercentage.value) {
                e.preventDefault();
                alert('Persentase diskon harus diisi untuk tipe diskon persentase');
                discountPercentage.focus();
                return false;
            }

            if (discountType.value === 'fixed' && !discountAmount.value) {
                e.preventDefault();
                alert('Nominal diskon harus diisi untuk tipe diskon nominal');
                discountAmount.focus();
                return false;
            }
        });

        discountType.addEventListener('change', toggleDiscountFields);
        toggleDiscountFields(); // Initial call based on current value
    });
</script>
@endsection