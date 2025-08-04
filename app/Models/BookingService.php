<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BookingService extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'booking_service';

    /**
     * Relasi ke referral code dari pivot table.
     */
    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class, 'referral_code_id');
    }
}
