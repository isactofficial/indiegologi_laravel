@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus-circle"></i> Buat Invoice Manual</h2>
    <a href="{{ route('admin.manual-invoices.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form action="{{ route('admin.manual-invoices.store') }}" method="POST" id="invoiceForm">
    @csrf
    
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
                            <input type="text" name="invoice_no" class="form-control" value="{{ $defaults['invoice_no'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Klien <span class="text-danger">*</span></label>
                            <input type="text" name="client_name" class="form-control" placeholder="Nama lengkap klien" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Klien</label>
                            <input type="email" name="client_email" class="form-control" placeholder="email@contoh.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP Klien</label>
                            <input type="text" name="client_phone" class="form-control" placeholder="0812 3456 7890">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Konseling</label>
                            <input type="text" name="counseling_date" class="form-control" placeholder="18 November 2024">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Paket Konseling</label>
                            <input type="text" name="package" class="form-control" placeholder="Personal Counseling">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipe Sesi</label>
                            <select name="session_type" class="form-select">
                                <option value="Offline" {{ ($defaults['session_type'] ?? '') == 'Offline' ? 'selected' : '' }}>Offline</option>
                                <option value="Online" {{ ($defaults['session_type'] ?? '') == 'Online' ? 'selected' : '' }}>Online</option>
                                <option value="Hybrid" {{ ($defaults['session_type'] ?? '') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Invoice <span class="text-danger">*</span></label>
                            <input type="date" name="invoice_date" class="form-control" value="{{ $defaults['invoice_date'] }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jatuh Tempo <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control" value="{{ $defaults['due_date'] }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Unpaid" {{ ($defaults['status'] ?? '') == 'Unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="Paid" {{ ($defaults['status'] ?? '') == 'Paid' ? 'selected' : '' }}>Lunas</option>
                                <option value="Overdue" {{ ($defaults['status'] ?? '') == 'Overdue' ? 'selected' : '' }}>Jatuh Tempo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Tipe Pembayaran</label>
                            <select name="payment_type" class="form-select">
                                <option value="Transfer" {{ ($defaults['payment_type'] ?? '') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="QRIS">QRIS</option>
                                <option value="Kartu Kredit">Kartu Kredit</option>
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
                        <!-- Items will be added here dynamically -->
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
                            <input type="number" name="discount_amount" class="form-control" id="discountAmount" value="0" min="0" onchange="calculateTotals()">
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
                    <textarea name="notes" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
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
                        <input type="text" name="company_name" class="form-control" value="{{ $defaults['company_name'] }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="company_email" class="form-control" value="{{ $defaults['company_email'] }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="company_phone" class="form-control" value="{{ $defaults['company_phone'] }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Website</label>
                        <input type="text" name="company_website" class="form-control" value="{{ $defaults['company_website'] }}">
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
                        <input type="text" name="bank_name" class="form-control" value="{{ $defaults['bank_name'] }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Rekening</label>
                        <input type="text" name="bank_account" class="form-control" value="{{ $defaults['bank_account'] }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Atas Nama</label>
                        <input type="text" name="account_name" class="form-control" value="{{ $defaults['account_name'] }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Konfirmasi WA</label>
                        <input type="text" name="confirm_number" class="form-control" value="{{ $defaults['confirm_number'] }}">
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
                        <input type="text" name="signed_by" class="form-control" value="{{ $defaults['signed_by'] }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="signed_title" class="form-control" value="{{ $defaults['signed_title'] }}">
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
                        <span id="summary-subtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Diskon:</span>
                        <span id="summary-discount">- Rp 0</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total:</span>
                        <span id="summary-total" class="text-primary">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Simpan Invoice
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
let itemIndex = 0;

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

// Add one main item by default on page load
document.addEventListener('DOMContentLoaded', function() {
    addMainItem();
});
</script>
@endpush
@endsection

