<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManualInvoice;
use App\Models\ManualInvoiceItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ManualInvoiceController extends Controller
{
    /**
     * Display a listing of the manual invoices.
     */
    public function index()
    {
        $invoices = ManualInvoice::with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.manual-invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new manual invoice.
     */
    public function create()
    {
        $invoiceNo = ManualInvoice::generateInvoiceNumber();
        
        // Default values
        $defaults = [
            'invoice_no' => $invoiceNo,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'company_name' => 'INDIEGOLOGI',
            'company_email' => 'temancerita@indiegologi.com',
            'company_phone' => '+62 822-2095-5595',
            'company_website' => 'indiegologi.com',
            'bank_name' => 'Bank SMBC Indonesia',
            'bank_account' => '90110023186',
            'account_name' => 'Artwira Mahatavirya Satyagasty',
            'confirm_number' => '0822 2095 5595',
            'signed_by' => 'Vernandika Stanley Hansen',
            'signed_title' => 'Indiegologi Team',
            'session_type' => 'Offline',
            'payment_type' => 'Transfer',
            'status' => 'Unpaid',
        ];

        return view('admin.manual-invoices.create', compact('defaults'));
    }

    /**
     * Store a newly created manual invoice in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:255',
            'invoice_no' => 'required|string|unique:manual_invoices,invoice_no',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create invoice
            $invoice = ManualInvoice::create([
                'invoice_no' => $request->invoice_no,
                'client_name' => $request->client_name,
                'client_email' => $request->client_email,
                'client_phone' => $request->client_phone,
                'counseling_date' => $request->counseling_date,
                'package' => $request->package,
                'session_type' => $request->session_type ?? 'Offline',
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'status' => $request->status ?? 'Unpaid',
                'payment_type' => $request->payment_type ?? 'Transfer',
                'company_name' => $request->company_name ?? 'INDIEGOLOGI',
                'company_email' => $request->company_email ?? 'temancerita@indiegologi.com',
                'company_phone' => $request->company_phone ?? '+62 822-2095-5595',
                'company_website' => $request->company_website ?? 'indiegologi.com',
                'bank_name' => $request->bank_name ?? 'Bank SMBC Indonesia',
                'bank_account' => $request->bank_account ?? '90110023186',
                'account_name' => $request->account_name ?? 'Artwira Mahatavirya Satyagasty',
                'confirm_number' => $request->confirm_number ?? '0822 2095 5595',
                'signed_by' => $request->signed_by ?? 'Vernandika Stanley Hansen',
                'signed_title' => $request->signed_title ?? 'Indiegologi Team',
                'discount_amount' => $request->discount_amount ?? 0,
                'notes' => $request->notes,
            ]);

            // Create items
            $subtotal = 0;
            foreach ($request->items as $itemData) {
                $lineTotal = $itemData['quantity'] * $itemData['unit_price'];
                $subtotal += $lineTotal;

                ManualInvoiceItem::create([
                    'manual_invoice_id' => $invoice->id,
                    'description' => $itemData['description'],
                    'subtitle' => $itemData['subtitle'] ?? null,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'is_addon' => isset($itemData['is_addon']) && $itemData['is_addon'] == '1',
                ]);
            }

            // Update totals
            $invoice->update([
                'subtotal_amount' => $subtotal,
                'total_amount' => $subtotal - ($request->discount_amount ?? 0),
            ]);

            DB::commit();

            return redirect()->route('admin.manual-invoices.index')
                ->with('success', 'Invoice berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(ManualInvoice $manualInvoice)
    {
        $manualInvoice->load('items');
        return view('admin.manual-invoices.edit', compact('manualInvoice'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, ManualInvoice $manualInvoice)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:255',
            'invoice_no' => 'required|string|unique:manual_invoices,invoice_no,' . $manualInvoice->id,
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update invoice
            $manualInvoice->update([
                'invoice_no' => $request->invoice_no,
                'client_name' => $request->client_name,
                'client_email' => $request->client_email,
                'client_phone' => $request->client_phone,
                'counseling_date' => $request->counseling_date,
                'package' => $request->package,
                'session_type' => $request->session_type ?? 'Offline',
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'status' => $request->status ?? 'Unpaid',
                'payment_type' => $request->payment_type ?? 'Transfer',
                'company_name' => $request->company_name ?? 'INDIEGOLOGI',
                'company_email' => $request->company_email ?? 'temancerita@indiegologi.com',
                'company_phone' => $request->company_phone ?? '+62 822-2095-5595',
                'company_website' => $request->company_website ?? 'indiegologi.com',
                'bank_name' => $request->bank_name ?? 'Bank SMBC Indonesia',
                'bank_account' => $request->bank_account ?? '90110023186',
                'account_name' => $request->account_name ?? 'Artwira Mahatavirya Satyagasty',
                'confirm_number' => $request->confirm_number ?? '0822 2095 5595',
                'signed_by' => $request->signed_by ?? 'Vernandika Stanley Hansen',
                'signed_title' => $request->signed_title ?? 'Indiegologi Team',
                'discount_amount' => $request->discount_amount ?? 0,
                'notes' => $request->notes,
            ]);

            // Delete old items and create new ones
            $manualInvoice->items()->delete();

            $subtotal = 0;
            foreach ($request->items as $itemData) {
                $lineTotal = $itemData['quantity'] * $itemData['unit_price'];
                $subtotal += $lineTotal;

                ManualInvoiceItem::create([
                    'manual_invoice_id' => $manualInvoice->id,
                    'description' => $itemData['description'],
                    'subtitle' => $itemData['subtitle'] ?? null,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'is_addon' => isset($itemData['is_addon']) && $itemData['is_addon'] == '1',
                ]);
            }

            // Update totals
            $manualInvoice->update([
                'subtotal_amount' => $subtotal,
                'total_amount' => $subtotal - ($request->discount_amount ?? 0),
            ]);

            DB::commit();

            return redirect()->route('admin.manual-invoices.index')
                ->with('success', 'Invoice berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(ManualInvoice $manualInvoice)
    {
        try {
            $manualInvoice->delete();
            return redirect()->route('admin.manual-invoices.index')
                ->with('success', 'Invoice berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for the specified invoice.
     */
    public function downloadPdf(ManualInvoice $manualInvoice)
    {
        $manualInvoice->load('items');
        
        $data = [
            'invoice' => $manualInvoice,
        ];

        $pdf = PDF::loadView('admin.manual-invoices.pdf', $data)
            ->setPaper('a4', 'portrait');

        $filename = 'invoice-' . str_replace('/', '-', $manualInvoice->invoice_no) . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Preview PDF in browser.
     */
    public function previewPdf(ManualInvoice $manualInvoice)
    {
        $manualInvoice->load('items');
        
        $data = [
            'invoice' => $manualInvoice,
        ];

        $pdf = PDF::loadView('admin.manual-invoices.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('invoice-' . str_replace('/', '-', $manualInvoice->invoice_no) . '.pdf');
    }

    /**
     * Show manual invoice preview page.
     */
    public function show(ManualInvoice $manualInvoice)
    {
        $manualInvoice->load('items');
        
        return view('admin.manual-invoices.show', [
            'invoice' => $manualInvoice,
        ]);
    }
}
