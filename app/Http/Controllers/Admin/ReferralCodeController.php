<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ReferralCodeController extends Controller
{
    /**
     * Display a listing of the referral codes.
     */
    public function index()
    {
        $referralCodes = ReferralCode::orderBy('created_at', 'desc')->paginate(10);
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
            'code'                => 'required|string|unique:referral_codes|max:255',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'max_uses'            => 'nullable|numeric|min:1',
            'valid_from'          => 'nullable|date',
            'valid_until'         => 'nullable|date|after:valid_from',
        ]);

        ReferralCode::create([
            'code'                => Str::upper($request->code),
            'discount_percentage' => $request->discount_percentage,
            'max_uses'            => $request->max_uses,
            'valid_from'          => $request->valid_from,
            'valid_until'         => $request->valid_until,
            'created_by'          => Auth::id(),
        ]);

        return redirect()->route('admin.referral-codes.index')->with('success', 'Kode referral berhasil ditambahkan!');
    }

    /**
     * Display the specified referral code.
     */
    public function show(ReferralCode $referralCode)
    {
        return view('referral-codes.show', compact('referralCode'));
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
            'code'                => 'required|string|unique:referral_codes,code,' . $referralCode->id,
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'max_uses'            => 'nullable|numeric|min:1',
            'valid_from'          => 'nullable|date',
            'valid_until'         => 'nullable|date|after:valid_from',
        ]);

        $referralCode->update([
            'code'                => Str::upper($request->code),
            'discount_percentage' => $request->discount_percentage,
            'max_uses'            => $request->max_uses,
            'valid_from'          => $request->valid_from,
            'valid_until'         => $request->valid_until,
        ]);

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
}
