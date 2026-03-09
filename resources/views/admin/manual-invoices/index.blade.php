@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-invoice"></i> Manual Invoice</h2>
    <a href="{{ route('admin.manual-invoices.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat Invoice Baru
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Invoice</th>
                        <th>Klien</th>
                        <th>Tanggal Invoice</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td>
                            <strong>{{ $invoice->invoice_no }}</strong>
                        </td>
                        <td>{{ $invoice->client_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                        <td>
                            @if($invoice->status == 'Paid')
                            <span class="badge bg-success">Lunas</span>
                            @elseif($invoice->status == 'Overdue')
                            <span class="badge bg-danger">Jatuh Tempo</span>
                            @else
                            <span class="badge bg-warning text-dark">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <strong>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.manual-invoices.show', $invoice->id) }}" 
                                   class="btn btn-sm btn-outline-info"
                                   title="Lihat Invoice">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.manual-invoices.preview-pdf', $invoice->id) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary"
                                   title="Preview PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('admin.manual-invoices.download-pdf', $invoice->id) }}" 
                                   class="btn btn-sm btn-outline-success"
                                   title="Download PDF">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('admin.manual-invoices.edit', $invoice->id) }}" 
                                   class="btn btn-sm btn-outline-warning"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.manual-invoices.destroy', $invoice->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada invoice manual</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</div>

@if($invoices->hasPages())
<div class="mt-4">
    {{ $invoices->links() }}
</div>
@endif
@endsection
