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
            border-radius: 0.5rem;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(12, 44, 90, 0.2);
        }

        .btn-sporty-primary:hover {
            background-color: #081f3f;
            border-color: #081f3f;
            transform: translateY(-1px);
            color: white;
        }

        .form-label.fw-medium {
            color: var(--theme-primary);
        }

        @media (max-width: 768px) {
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
    </style>
    <div class="container-fluid px-4" style="min-height: 100vh;">
        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-white rounded-4 shadow-sm p-4" style="border-left: 8px solid #0C2C5A;">
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-center align-items-center rounded-circle me-4"
                            style="width: 70px; height: 70px; background-color: rgba(12, 44, 90, 0.1);">
                            <i class="fas fa-edit fs-2" style="color: #0C2C5A;"></i>
                        </div>
                        <div>
                            <h2 class="fs-3 fw-bold mb-1" style="color: #0C2C5A;">Edit Jadwal Konsultasi</h2>
                            <p class="text-muted mb-0">Ubah detail slot jadwal: **{{ $schedule->formatted_date }}** pukul **{{ $schedule->formatted_time }}**.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="card shadow-sm border-0 mb-4 rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.free-consultation.schedules.update', $schedule->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="type_id" class="form-label text-secondary fw-medium">Tipe Layanan</label>
                            <select id="type_id" name="type_id" class="form-select @error('type_id') is-invalid @enderror" required>
                                <option value="" disabled>Pilih Tipe Layanan</option>
                                @foreach ($types as $type)
                                    @php
                                        $selectedValue = old('type_id', $schedule->type_id);
                                    @endphp
                                    <option value="{{ $type->id }}" {{ $selectedValue == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="scheduled_date" class="form-label text-secondary fw-medium">Tanggal Jadwal</label>
                            <input type="date" id="scheduled_date" name="scheduled_date"
                                class="form-control @error('scheduled_date') is-invalid @enderror" 
                                value="{{ old('scheduled_date', $schedule->scheduled_date ? $schedule->scheduled_date->format('Y-m-d') : '') }}"
                                required> 
                            @error('scheduled_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="scheduled_time" class="form-label text-secondary fw-medium">Waktu Jadwal (HH:mm)</label>
                            <input type="time" id="scheduled_time" name="scheduled_time"
                                class="form-control @error('scheduled_time') is-invalid @enderror" 
                                value="{{ old('scheduled_time', $schedule->scheduled_time ? $schedule->scheduled_time->format('H:i') : '') }}"
                                required>
                            @error('scheduled_time')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="current_bookings" class="form-label text-secondary fw-medium">Pemesanan Saat Ini</label>
                            <input type="number" id="current_bookings" name="current_bookings"
                                class="form-control @error('current_bookings') is-invalid @enderror" 
                                value="{{ old('current_bookings', $schedule->current_bookings) }}"
                                required min="0" readonly>
                            @error('current_bookings')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Nilai ini tidak dapat diubah secara manual.</small>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="max_participants" class="form-label text-secondary fw-medium">Kapasitas Maksimal Peserta</label>
                            <input type="number" id="max_participants" name="max_participants"
                                class="form-control @error('max_participants') is-invalid @enderror" 
                                value="{{ old('max_participants', $schedule->max_participants) }}"
                                required min="{{ $schedule->current_bookings }}">
                            @error('max_participants')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @if ($schedule->current_bookings > 0)
                                <small class="form-text text-danger">Kapasitas minimum adalah {{ $schedule->current_bookings }} (jumlah pemesanan saat ini).</small>
                            @endif
                        </div>
                        
                        <div class="col-md-12 mb-4">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_available" value="0">
                                
                                @php
                                    $isChecked = old('is_available', $schedule->is_available) == 1;
                                @endphp

                                <input class="form-check-input @error('is_available') is-invalid @enderror" type="checkbox" id="is_available" name="is_available" value="1" 
                                    style="margin-top: 0.25rem;" 
                                    {{ $isChecked ? 'checked' : '' }}>
                                    
                                <label class="form-check-label text-secondary fw-medium" for="is_available">Aktifkan Jadwal (Tersedia untuk dipesan)</label>
                            </div>
                            @error('is_available')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    
                    <div class="d-flex justify-content-end mt-4 form-actions">
                        <a href="{{ route('admin.free-consultation.schedules.index') }}"
                            class="btn btn-outline-secondary px-4 py-2">Batal</a>
                        <button type="submit" class="btn btn-sporty-primary ms-2 px-4 py-2">
                            Perbarui Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection