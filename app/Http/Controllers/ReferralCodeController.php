<?php

namespace App\Http\Controllers;

use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReferralCodeController extends Controller
{
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

        $discountAmount = $code->calculateDiscount($request->total_amount);
        $finalAmount = $request->total_amount - $discountAmount;

        return response()->json([
            'success' => true,
            'discount_type' => $code->discount_type,
            'discount_percentage' => $code->discount_type === 'percentage' ? (float) $code->discount_percentage : null,
            'discount_amount' => $code->discount_type === 'fixed' ? (float) $code->discount_amount : null,
            'calculated_discount' => $discountAmount,
            'final_amount' => $finalAmount,
            'message' => 'Kode referral berhasil diterapkan'
        ]);
    }

    public function applyCode(Request $request)
    {
        // Method ini bisa digabung dengan validateCode atau dihapus
        return $this->validateCode($request);
    }
}