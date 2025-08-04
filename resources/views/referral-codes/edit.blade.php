@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4" style="min-height: 100vh;">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #f4b704;">
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                         style="width: 70px; height: 70px; background-color: rgba(244, 183, 4, 0.1);">
                        <i class="fas fa-tags fs-2" style="color: #f4b704;"></i>
                    </div>
                    <div>
                        <h2 class="fs-3 fw-bold mb-1" style="color: #f4b704;">Edit Kode Referral</h2>
                        <p class="text-muted mb-0">Perbarui detail kode referral.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.referral-codes.update', $referralCode->id) }}" method="POST">
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
                        <label for="discount_percentage" class="form-label text-secondary fw-medium">Persentase Diskon (%)</label>
                        <input type="number" id="discount_percentage" name="discount_percentage" class="form-control @error('discount_percentage') is-invalid @enderror" value="{{ old('discount_percentage', $referralCode->discount_percentage) }}" min="0" max="100" required>
                        @error('discount_percentage')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
@endsection
