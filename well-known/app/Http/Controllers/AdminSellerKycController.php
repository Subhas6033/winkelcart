<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\SellerKycVerification;
use App\Models\User;
use App\Services\ShiprocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use App\Mail\SellerKycApprovedNotification;
use App\Mail\SellerKycRejectedNotification;

class AdminSellerKycController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    private function ensureAdminAccess()
    {
        abort_unless(Auth::user() && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin')), 403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->ensureAdminAccess();

        $query = User::role('Seller')
            ->with(['user_info', 'kycVerification'])
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('mobile', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'Not Submitted') {
                $query->whereDoesntHave('kycVerification');
            } else {
                $query->whereHas('kycVerification', function ($kycQuery) use ($request) {
                    $kycQuery->where('status', $request->status);
                });
            }
        }

        $sellers = $query->paginate(20)->appends($request->query());
        $statusOptions = ['Pending', 'Verified', 'Rejected', 'Not Submitted'];

        return view('admin.seller_kyc.index', compact('sellers', 'statusOptions'));
    }

    public function upsert(Request $request, User $seller)
    {
        $this->ensureAdminAccess();

        abort_unless($seller->hasRole('Seller'), 404);

        $requiredColumns = [
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
            'mobile_verified_at',
            'email_verified_at',
        ];

        foreach ($requiredColumns as $column) {
            if (!Schema::hasColumn('seller_kyc_verifications', $column)) {
                return back()->withInput()->withErrors([
                    'error' => 'KYC schema is outdated. Please run database migrations before verifying sellers.',
                ]);
            }
        }

        $validated = $request->validate([
            'status' => 'required|in:Pending,Verified,Rejected',
            'legal_name' => 'nullable|string|max:190|required_if:status,Verified',
            'pan_number' => 'nullable|string|max:30|required_if:status,Verified',
            'aadhaar_number' => 'nullable|string|max:20|required_if:status,Verified',
            'bank_account_holder' => 'nullable|string|max:190|required_if:status,Verified',
            'bank_account_number' => 'nullable|string|max:50|required_if:status,Verified',
            'bank_ifsc_code' => 'nullable|string|max:20|required_if:status,Verified',
            'bank_name' => 'nullable|string|max:190|required_if:status,Verified',
            'gst_number' => 'nullable|string|max:20|required_if:status,Verified',
            'bank_passbook_reference' => 'nullable|string|max:255|required_if:status,Verified',
            'id_card_reference' => 'nullable|string|max:255|required_if:status,Verified',
            'pan_card_reference' => 'nullable|string|max:255|required_if:status,Verified',
            'gst_certificate_reference' => 'nullable|string|max:255|required_if:status,Verified',
            'membership_payment_screenshot_reference' => 'nullable|string|max:255',
            'membership_payment_verified' => 'nullable|boolean',
            'admin_note' => 'nullable|string|max:3000',
            'shiprocket_pickup_location' => 'nullable|string|max:255',
            'shiprocket_pickup_id'       => 'nullable|string|max:255',
            'pickup_address'             => 'nullable|string|max:300',
            'pickup_address_2'           => 'nullable|string|max:300',
            'pickup_city'                => 'nullable|string|max:100',
            'pickup_state'               => 'nullable|string|max:100',
            'pickup_pincode'             => 'nullable|digits:6',
            'pickup_phone'               => 'nullable|digits:10',
            'pickup_email'               => 'nullable|email|max:190',
        ]);

        if ($validated['status'] === 'Verified') {
            foreach ([
                'legal_name',
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
            ] as $field) {
                if (empty(trim((string) ($validated[$field] ?? '')))) {
                    return back()->withInput()->withErrors([
                        $field => ucfirst(str_replace('_', ' ', $field)) . ' is required for full KYC verification.',
                    ]);
                }
            }

            if (empty(trim((string) $seller->mobile)) || empty(trim((string) $seller->email))) {
                return back()->withInput()->withErrors([
                    'status' => 'Seller mobile and email are mandatory before marking KYC as verified.',
                ]);
            }

            // Payment check temporarily disabled
            // $existingKyc = SellerKycVerification::where('user_id', $seller->id)->first();
            // $hasRazorpayPayment = $existingKyc && !empty($existingKyc->razorpay_payment_id) && $existingKyc->payment_status === 'Verified';
            // $hasManualApproval = !empty($validated['membership_payment_verified']);

            // if (!$hasRazorpayPayment && !$hasManualApproval) {
            //     return back()->withInput()->withErrors([
            //         'membership_payment_verified' => 'Membership payment must be completed via Razorpay or manually approved before marking KYC as Verified.',
            //     ]);
            // }
        }

        $kyc = SellerKycVerification::firstOrNew(['user_id' => $seller->id]);

        $oldValues = [
            'status' => $kyc->status,
            'admin_note' => $kyc->admin_note,
            'mobile_verified_at' => $kyc->mobile_verified_at,
            'email_verified_at' => $kyc->email_verified_at,
            'verified_at' => $kyc->verified_at,
        ];

        $kyc->legal_name = $validated['legal_name'] ?? ($kyc->legal_name ?: $seller->name);
        $kyc->pan_number = isset($validated['pan_number']) ? strtoupper(trim((string) $validated['pan_number'])) : $kyc->pan_number;
        $kyc->aadhaar_number = isset($validated['aadhaar_number']) ? preg_replace('/\s+/', '', trim((string) $validated['aadhaar_number'])) : $kyc->aadhaar_number;
        $kyc->bank_account_holder = $validated['bank_account_holder'] ?? $kyc->bank_account_holder;
        $kyc->bank_account_number = isset($validated['bank_account_number']) ? preg_replace('/\s+/', '', trim((string) $validated['bank_account_number'])) : $kyc->bank_account_number;
        $kyc->bank_ifsc_code = isset($validated['bank_ifsc_code']) ? strtoupper(trim((string) $validated['bank_ifsc_code'])) : $kyc->bank_ifsc_code;
        $kyc->bank_name = $validated['bank_name'] ?? $kyc->bank_name;
        $kyc->gst_number = isset($validated['gst_number']) ? strtoupper(trim((string) $validated['gst_number'])) : $kyc->gst_number;
        $kyc->bank_passbook_reference = $validated['bank_passbook_reference'] ?? $kyc->bank_passbook_reference;
        $kyc->id_card_reference = $validated['id_card_reference'] ?? $kyc->id_card_reference;
        $kyc->pan_card_reference = $validated['pan_card_reference'] ?? $kyc->pan_card_reference;
        $kyc->gst_certificate_reference = $validated['gst_certificate_reference'] ?? $kyc->gst_certificate_reference;
        $kyc->membership_payment_screenshot_reference = $validated['membership_payment_screenshot_reference'] ?? $kyc->membership_payment_screenshot_reference;
        $newPickupLocation = isset($validated['shiprocket_pickup_location']) ? trim($validated['shiprocket_pickup_location']) : $kyc->shiprocket_pickup_location;
        $newPickupAddress  = isset($validated['pickup_address']) ? trim($validated['pickup_address']) : $kyc->pickup_address;

        // Reset sync whenever the pickup name or address is changed by admin
        $pickupChanged = $newPickupLocation !== $kyc->shiprocket_pickup_location
            || $newPickupAddress !== $kyc->pickup_address;

        $kyc->shiprocket_pickup_location = $newPickupLocation;
        $kyc->shiprocket_pickup_id       = $validated['shiprocket_pickup_id'] ?? $kyc->shiprocket_pickup_id;
        $kyc->pickup_address             = $newPickupAddress;
        $kyc->pickup_address_2           = isset($validated['pickup_address_2']) ? trim($validated['pickup_address_2']) : $kyc->pickup_address_2;
        $kyc->pickup_city                = $validated['pickup_city'] ?? $kyc->pickup_city;
        $kyc->pickup_state               = $validated['pickup_state'] ?? $kyc->pickup_state;
        $kyc->pickup_pincode             = $validated['pickup_pincode'] ?? $kyc->pickup_pincode;
        $kyc->pickup_phone               = $validated['pickup_phone'] ?? $kyc->pickup_phone;
        $kyc->pickup_email               = isset($validated['pickup_email']) ? trim($validated['pickup_email']) : $kyc->pickup_email;
        if ($pickupChanged) {
            $kyc->pickup_sync_status = null;
            $kyc->pickup_synced_at   = null;
        }
        if (Schema::hasColumn('seller_kyc_verifications', 'membership_payment_verified')) {
            // Auto-approve if Razorpay payment exists, otherwise use admin checkbox
            if (!empty($kyc->razorpay_payment_id) && $kyc->payment_status === 'Verified') {
                $kyc->membership_payment_verified = true;
            } else {
                $kyc->membership_payment_verified = !empty($validated['membership_payment_verified']) ? true : false;
            }
        }
        $kyc->id_type = 'KYC_V2_DOCS';
        $kyc->id_number = trim((string) $kyc->pan_number) . ' / ' . trim((string) $kyc->aadhaar_number);
        $kyc->document_reference = $kyc->bank_passbook_reference;
        $kyc->status = $validated['status'];
        $kyc->admin_note = $validated['admin_note'] ?? null;
        $kyc->verified_by = Auth::id();
        $kyc->verified_at = $validated['status'] === 'Verified' ? now() : null;
        $kyc->mobile_verified_at = $validated['status'] === 'Verified' ? now() : null;
        $kyc->email_verified_at = $validated['status'] === 'Verified' ? now() : null;
        $kyc->save();

        // Determine if status actually changed
        $oldStatus = $oldValues['status'];
        $newStatus = $kyc->status;
        $emailSent = false;
        $emailType = null;

        \Log::info("KYC upsert for seller #{$seller->id}: old_status={$oldStatus}, new_status={$newStatus}, email={$seller->email}");

        // Send approval email when status changes to Verified (or is set to Verified on a new record)
        if ($newStatus === 'Verified' && $oldStatus !== 'Verified' && !empty($seller->email)) {
            try {
                Mail::to($seller->email)->send(new SellerKycApprovedNotification($seller));
                $emailSent = true;
                $emailType = 'approval';
                \Log::info("KYC approval email sent to seller #{$seller->id} ({$seller->email})");
            } catch (\Exception $e) {
                \Log::error('Failed to send KYC approval email to seller #' . $seller->id . ': ' . $e->getMessage());
            }

            // Sync the seller's pickup location with Shiprocket immediately on approval.
            // This ensures the pickup location exists in Shiprocket BEFORE any order is placed.
            try {
                $kyc->load('seller.user_info'); // reload relationships to get fresh data
                $shiprocket = new ShiprocketService();
                $synced     = $shiprocket->syncSellerPickupLocation($kyc);

                if ($synced) {
                    Log::info("Shiprocket pickup location synced for seller #{$seller->id}");
                } else {
                    Log::warning(
                        "Shiprocket pickup sync failed for seller #{$seller->id}. " .
                        'Admin should verify the pickup address fields are complete on this KYC record.'
                    );
                }
            } catch (\Exception $e) {
                Log::error('Shiprocket pickup sync exception for seller #' . $seller->id . ': ' . $e->getMessage());
            }
        }

        // Send rejection email when status changes to Rejected (or is set to Rejected on a new record)
        if ($newStatus === 'Rejected' && $oldStatus !== 'Rejected' && !empty($seller->email)) {
            try {
                Mail::to($seller->email)->send(new SellerKycRejectedNotification($seller, $kyc->admin_note));
                $emailSent = true;
                $emailType = 'rejection';
                \Log::info("KYC rejection email sent to seller #{$seller->id} ({$seller->email})");
            } catch (\Exception $e) {
                \Log::error('Failed to send KYC rejection email to seller #' . $seller->id . ': ' . $e->getMessage());
            }
        }

        AuditLog::record(
            'seller_kyc.updated',
            'seller_kyc_verification',
            $kyc->id,
            $oldValues,
            [
                'seller_id' => $seller->id,
                'status' => $kyc->status,
                'admin_note' => $kyc->admin_note,
                'mobile_verified_at' => $kyc->mobile_verified_at,
                'email_verified_at' => $kyc->email_verified_at,
                'verified_at' => $kyc->verified_at,
            ]
        );

        $successMsg = 'Seller KYC updated successfully.';
        if ($emailSent) {
            $successMsg .= " A {$emailType} notification email was sent to {$seller->email}.";
        } elseif ($oldStatus === $newStatus) {
            $successMsg .= " No email sent (status unchanged: {$newStatus}).";
        }

        return back()->with('success', $successMsg);
    }
}
