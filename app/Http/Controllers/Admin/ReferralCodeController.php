<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReferralCodeController extends Controller
{
    /**
     * Display a listing of the referral codes.
     */
    public function index()
    {
        $referralCodes = ReferralCode::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('referral-codes.index', compact('referralCodes'));
    }

    /**
     * Show the form for creating a new referral code.
     */
    public function create()
    {
        return view('referral-codes.create');
    }

    /**
     * Store a newly created referral code in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:referral_codes|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_percentage' => 'required_if:discount_type,percentage|nullable|numeric|min:0|max:100',
            'discount_amount' => 'required_if:discount_type,fixed|nullable|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'sometimes|boolean', // PERBAIKAN: tambahkan sometimes|boolean
        ]);

        // Prepare data dengan handling null values
        $data = [
            'code' => Str::upper($request->code),
            'discount_type' => $request->discount_type,
            'min_purchase_amount' => $request->min_purchase_amount,
            'max_uses' => $request->max_uses,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true, // PERBAIKAN: handle checkbox
            'created_by' => Auth::id(),
        ];

        // Handle discount values berdasarkan tipe
        if ($request->discount_type === 'percentage') {
            $data['discount_percentage'] = $request->discount_percentage;
            $data['discount_amount'] = null;
        } else {
            $data['discount_amount'] = $request->discount_amount;
            $data['discount_percentage'] = null;
        }

        ReferralCode::create($data);

        return redirect()->route('admin.referral-codes.index')->with('success', 'Kode referral berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified referral code.
     */
    public function edit(ReferralCode $referralCode)
    {
        return view('referral-codes.edit', compact('referralCode'));
    }

    /**
     * Update the specified referral code in storage.
     */
    public function update(Request $request, ReferralCode $referralCode)
    {
        $request->validate([
            'code' => 'required|string|unique:referral_codes,code,' . $referralCode->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_percentage' => 'required_if:discount_type,percentage|nullable|numeric|min:0|max:100',
            'discount_amount' => 'required_if:discount_type,fixed|nullable|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'sometimes|boolean', // PERBAIKAN: tambahkan sometimes|boolean
        ]);

        // Prepare data dengan handling null values
        $data = [
            'code' => Str::upper($request->code),
            'discount_type' => $request->discount_type,
            'min_purchase_amount' => $request->min_purchase_amount,
            'max_uses' => $request->max_uses,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false, // PERBAIKAN: handle checkbox
        ];

        // Handle discount values berdasarkan tipe
        if ($request->discount_type === 'percentage') {
            $data['discount_percentage'] = $request->discount_percentage;
            $data['discount_amount'] = null;
        } else {
            $data['discount_amount'] = $request->discount_amount;
            $data['discount_percentage'] = null;
        }

        $referralCode->update($data);

        return redirect()->route('admin.referral-codes.index')->with('success', 'Kode referral berhasil diperbarui!');
    }

    /**
     * Remove the specified referral code from storage.
     */
    public function destroy(ReferralCode $referralCode)
    {
        $referralCode->delete();
        return redirect()->route('admin.referral-codes.index')->with('success', 'Kode referral berhasil dihapus!');
    }

    /**
     * Toggle status aktif/nonaktif referral code
     */
    public function toggleStatus(ReferralCode $referralCode)
    {
        $referralCode->update([
            'is_active' => !$referralCode->is_active
        ]);

        $status = $referralCode->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.referral-codes.index')->with('success', "Kode referral berhasil $status!");
    }

    public function validateCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid'
            ]);
        }

        $code = ReferralCode::where('code', $request->code)->first();

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Kode referral tidak valid'
            ]);
        }

        if (!$code->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode referral tidak dapat digunakan'
            ]);
        }

        // Calculate discount for the current total amount
        $discountAmount = $code->calculateDiscount($request->total_amount);
        $finalAmount = $request->total_amount - $discountAmount;

        return response()->json([
            'success' => true,
            'discount_type' => $code->discount_type,
            'discount_percentage' => $code->discount_type === 'percentage' ? (float) $code->discount_percentage : null,
            'discount_amount' => $code->discount_type === 'fixed' ? (float) $code->discount_amount : null,
            'calculated_discount' => $discountAmount, // Total discount untuk jumlah peserta saat ini
            'final_amount' => $finalAmount,
            'message' => 'Kode referral berhasil diterapkan'
        ]);
    }
}
