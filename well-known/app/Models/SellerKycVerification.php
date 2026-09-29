<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SellerKycVerification extends Model
{
    use HasFactory;

    protected $table = 'seller_kyc_verifications';

    protected $fillable = [
        'user_id',
        'legal_name',
        'shiprocket_pickup_location',
        'shiprocket_pickup_id',
        'pickup_address',
        'pickup_address_2',
        'pickup_city',
        'pickup_state',
        'pickup_pincode',
        'pickup_phone',
        'pickup_email',
        'pickup_sync_status',
        'pickup_synced_at',
        'pan_number',
        'aadhaar_number',
        'bank_account_holder',
        'bank_account_number',
        'bank_ifsc_code',
        'bank_name',
        'gst_number',
        'bank_passbook_reference',
        'id_card_reference',
        'pan_card_reference',
        'gst_certificate_reference',
        'membership_payment_screenshot_reference',
        'membership_payment_verified',
        'address_proof_reference',
        'selfie_with_id_reference',
        'mobile_verified_at',
        'email_verified_at',
        'id_type',
        'id_number',
        'document_reference',
        'status',
        'admin_note',
        'verified_by',
        'verified_at',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'payment_status',
        'membership_start',
        'membership_expiry',
    ];

    protected $casts = [
        'verified_at'                => 'datetime',
        'mobile_verified_at'         => 'datetime',
        'email_verified_at'          => 'datetime',
        'membership_payment_verified' => 'boolean',
        'membership_start'           => 'datetime',
        'membership_expiry'          => 'datetime',
        'pickup_synced_at'           => 'datetime',
    ];

    /**
     * True if this seller's pickup location is confirmed in Shiprocket.
     */
    public function isPickupSynced(): bool
    {
        return $this->pickup_sync_status === 'synced';
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeFullyVerified($query)
    {
        $query = $query
            ->where('status', 'Verified')
            ->whereNotNull('legal_name')
            ->where('legal_name', '!=', '');

        // Backward compatibility for environments where extended columns are not migrated yet.
        if (!Schema::hasColumn($this->getTable(), 'pan_number')) {
            return $query
                ->whereNotNull('id_type')
                ->where('id_type', '!=', '')
                ->whereNotNull('id_number')
                ->where('id_number', '!=', '')
                ->whereNotNull('document_reference')
                ->where('document_reference', '!=', '');
        }

        $query = $query
            ->whereNotNull('pan_number')
            ->where('pan_number', '!=', '')
            ->whereNotNull('aadhaar_number')
            ->where('aadhaar_number', '!=', '')
            ->whereNotNull('bank_account_holder')
            ->where('bank_account_holder', '!=', '')
            ->whereNotNull('bank_account_number')
            ->where('bank_account_number', '!=', '')
            ->whereNotNull('bank_ifsc_code')
            ->where('bank_ifsc_code', '!=', '')
            ->whereNotNull('bank_name')
            ->where('bank_name', '!=', '')
            ->whereNotNull('gst_number')
            ->where('gst_number', '!=', '');

        if (!Schema::hasColumn($this->getTable(), 'bank_passbook_reference')) {
            return $query
                ->whereNotNull('address_proof_reference')
                ->where('address_proof_reference', '!=', '')
                ->whereNotNull('selfie_with_id_reference')
                ->where('selfie_with_id_reference', '!=', '')
                ->whereNotNull('mobile_verified_at')
                ->whereNotNull('email_verified_at');
        }

        return $query
            ->whereNotNull('bank_passbook_reference')
            ->where('bank_passbook_reference', '!=', '')
            ->whereNotNull('id_card_reference')
            ->where('id_card_reference', '!=', '')
            ->whereNotNull('pan_card_reference')
            ->where('pan_card_reference', '!=', '')
            ->whereNotNull('gst_certificate_reference')
            ->where('gst_certificate_reference', '!=', '')
            ->whereNotNull('mobile_verified_at')
            ->whereNotNull('email_verified_at');
    }

    public function isFullyVerified(): bool
    {
        if ($this->status !== 'Verified' || empty(trim((string) $this->legal_name))) {
            return false;
        }

        // Backward compatibility for environments where extended columns are not migrated yet.
        if (!Schema::hasColumn($this->getTable(), 'pan_number')) {
            return !empty(trim((string) $this->id_type))
                && !empty(trim((string) $this->id_number))
                && !empty(trim((string) $this->document_reference));
        }

        return !empty(trim((string) $this->pan_number))
            && !empty(trim((string) $this->aadhaar_number))
            && !empty(trim((string) $this->bank_account_holder))
            && !empty(trim((string) $this->bank_account_number))
            && !empty(trim((string) $this->bank_ifsc_code))
            && !empty(trim((string) $this->bank_name))
            && !empty(trim((string) $this->gst_number))
            && (
                !Schema::hasColumn($this->getTable(), 'bank_passbook_reference')
                    ? (
                        !empty(trim((string) $this->address_proof_reference))
                        && !empty(trim((string) $this->selfie_with_id_reference))
                    )
                    : (
                        !empty(trim((string) $this->bank_passbook_reference))
                        && !empty(trim((string) $this->id_card_reference))
                        && !empty(trim((string) $this->pan_card_reference))
                        && !empty(trim((string) $this->gst_certificate_reference))
                    )
            )
            && !empty($this->mobile_verified_at)
            && !empty($this->email_verified_at);
    }
}
