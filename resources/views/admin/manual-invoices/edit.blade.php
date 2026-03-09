@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit"></i> Edit Invoice Manual</h2>
    <a href="{{ route('admin.manual-invoices.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form action="{{ route('admin.manual-invoices.update', $manualInvoice->id) }}" method="POST" id="invoiceForm">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Left Column - Invoice Details -->
        <div class="col-lg-8">
            <!-- Client & Invoice Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Klien & Invoice</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">No. Invoice <span class="text-danger">*</span></label>
                            <input type="text" name="invoice_no" class="form-control" value="{{ $manualInvoice->invoice_no }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Klien <span class="text-danger">*</span></label>
                            <input type="text" name="client_name" class="form-control" value="{{ $manualInvoice->client_name }}" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Klien</label>
                            <input type="email" name="client_email" class="form-control" value="{{ $manualInvoice->client_email }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP Klien</label>
                            <input type="text" name="client_phone" class="form-control" value="{{ $manualInvoice->client_phone }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Konseling</label>
                            <input type="text" name="counseling_date" class="form-control" value="{{ $manualInvoice->counseling_date }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Paket Konseling</label>
                            <input type="text" name="package" class="form-control" value="{{ $manualInvoice->package }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipe Sesi</label>
                            <select name="session_type" class="form-select">
                                <option value="Offline" {{ $manualInvoice->session_type == 'Offline' ? 'selected' : '' }}>Offline</option>
                                <option value="Online" {{ $manualInvoice->session_type == 'Online' ? 'selected' : '' }}>Online</option>
                                <option value="Hybrid" {{ $manualInvoice->session_type == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Invoice <span class="text-danger">*</span></label>
                            <input type="date" name="invoice_date" class="form-control" value="{{ $manualInvoice->invoice_date->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jatuh Tempo <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control" value="{{ $manualInvoice->due_date->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Unpaid" {{ $manualInvoice->status == 'Unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="Paid" {{ $manualInvoice->status == 'Paid' ? 'selected' : '' }}>Lunas</option>
                                <option value="Overdue" {{ $manualInvoice->status == 'Overdue' ? 'selected' : '' }}>Jatuh Tempo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Tipe Pembayaran</label>
                            <select name="payment_type" class="form-select">
                                <option value="Transfer" {{ $manualInvoice->payment_type == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="Cash" {{ $manualInvoice->payment_type == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="QRIS" {{ $manualInvoice->payment_type == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                <option value="Kartu Kredit" {{ $manualInvoice->payment_type == 'Kartu Kredit' ? 'selected' : '' }}>Kartu Kredit</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Item Invoice</h5>
                </div>
                <div class="card-body">
                    <div id="items-container">
                        @foreach($manualInvoice->items as $item)
                        <div class="card mb-3 item-card" id="item-{{ $loop->index }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge {{ $item->is_addon ? 'bg-info' : 'bg-primary' }}">{{ $item->is_addon ? 'Add-On' : 'Main' }}</span>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem({{ $loop->index }})">
                                        <i class="fas fa-times"></i> Hapus
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                        <input type="text" name="items[{{ $loop->index }}][description]" class="form-control" value="{{ $item->description }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Sub-deskripsi</label>
                                        <input type="text" name="items[{{ $loop->index }}][subtitle]" class="form-control" value="{{ $item->subtitle }}">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="items[{{ $loop->index }}][quantity]" class="form-control item-quantity" value="{{ $item->quantity }}" min="1" onchange="calculateTotals()">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Harga Satuan (Rp)</label>
                                        <input type="number" name="items[{{ $loop->index }}][unit_price]" class="form-control item-price" value="{{ $item->unit_price }}" min="0" onchange="calculateTotals()">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Total</label>
                                        <input type="text" class="form-control item-total" value="Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}" readonly>
                                        <input type="hidden" name="items[{{ $loop->index }}][is_addon]" value="{{ $item->is_addon ? '1' : '0' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary" onclick="addMainItem()">
                        <i class="fas fa-plus"></i> Tambah Item Utama
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="addAddonItem()">
                        <i class="fas fa-plus"></i> Tambah Add-On
                    </button>
                </div>
            </div>

            <!-- Discount -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-tags"></i> Diskon</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Nominal Diskon (Rp)</label>
                            <input type="number" name="discount_amount" class="form-control" id="discountAmount" value="{{ $manualInvoice->discount_amount }}" min="0" onchange="calculateTotals()">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Catatan</h5>
                </div>
                <div class="card-body">
                    <textarea name="notes" class="form-control" rows="3">{{ $manualInvoice->notes }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right Column - Company Info & Summary -->
        <div class="col-lg-4">
            <!-- Company Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-building"></i> Informasi Perusahaan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan</label>
                        <input type="text" name="company_name" class="form-control" value="{{ $manualInvoice->company_name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="company_email" class="form-control" value="{{ $manualInvoice->company_email }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="company_phone" class="form-control" value="{{ $manualInvoice->company_phone }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Website</label>
                        <input type="text" name="company_website" class="form-control" value="{{ $manualInvoice->company_website }}">
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-university"></i> Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Bank</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ $manualInvoice->bank_name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Rekening</label>
                        <input type="text" name="bank_account" class="form-control" value="{{ $manualInvoice->bank_account }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Atas Nama</label>
                        <input type="text" name="account_name" class="form-control" value="{{ $manualInvoice->account_name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Konfirmasi WA</label>
                        <input type="text" name="confirm_number" class="form-control" value="{{ $manualInvoice->confirm_number }}">
                    </div>
                </div>
            </div>

            <!-- Signature -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-pen"></i> Tanda Tangan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Penandatangan</label>
                        <input type="text" name="signed_by" class="form-control" value="{{ $manualInvoice->signed_by }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="signed_title" class="form-control" value="{{ $manualInvoice->signed_title }}">
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calculator"></i> Ringkasan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="summary-subtotal">Rp {{ number_format($manualInvoice->subtotal_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Diskon:</span>
                        <span id="summary-discount">- Rp {{ number_format($manualInvoice->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total:</span>
                        <span id="summary-total" class="text-primary">Rp {{ number_format($manualInvoice->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Update Invoice
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
let itemIndex = {{ $manualInvoice->items->count() }};

function formatCurrency(amount) {
    return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
}

function addMainItem() {
    addItem(false);
}

function addAddonItem() {
    addItem(true);
}

function addItem(isAddon) {
    const container = document.getElementById('items-container');
    const badgeClass = isAddon ? 'bg-info' : 'bg-primary';
    const badgeText = isAddon ? 'Add-On' : 'Main';
    
    const html = `
        <div class="card mb-3 item-card" id="item-${itemIndex}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge ${badgeClass}">${badgeText}</span>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${itemIndex})">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" name="items[${itemIndex}][description]" class="form-control" placeholder="Nama layanan" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sub-deskripsi</label>
                        <input type="text" name="items[${itemIndex}][subtitle]" class="form-control" placeholder="Opsional">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="items[${itemIndex}][quantity]" class="form-control item-quantity" value="1" min="1" onchange="calculateTotals()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Harga Satuan (Rp)</label>
                        <input type="number" name="items[${itemIndex}][unit_price]" class="form-control item-price" value="0" min="0" onchange="calculateTotals()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total</label>
                        <input type="text" class="form-control item-total" value="Rp 0" readonly>
                        <input type="hidden" name="items[${itemIndex}][is_addon]" value="${isAddon ? '1' : '0'}">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
    itemIndex++;
    calculateTotals();
}

function removeItem(index) {
    document.getElementById(`item-${index}`).remove();
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    
    document.querySelectorAll('.item-card').forEach(card => {
        const quantity = parseFloat(card.querySelector('.item-quantity').value) || 0;
        const unitPrice = parseFloat(card.querySelector('.item-price').value) || 0;
        const lineTotal = quantity * unitPrice;
        
        card.querySelector('.item-total').value = formatCurrency(lineTotal);
        subtotal += lineTotal;
    });
    
    const discount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const total = Math.max(0, subtotal - discount);
    
    document.getElementById('summary-subtotal').textContent = formatCurrency(subtotal);
    document.getElementById('summary-discount').textContent = '- ' + formatCurrency(discount);
    document.getElementById('summary-total').textContent = formatCurrency(total);
}

// Calculate initial totals
document.addEventListener('DOMContentLoaded', function() {
    calculateTotals();
});
</script>
@endpush
@endsection

