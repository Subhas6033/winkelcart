<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Cart;
use App\Models\Order;
use App\Models\AdminNotification;
use App\Models\SellerKycVerification;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\SellerKycSubmittedNotification;
use App\Mail\AdminKycSubmittedNotification;

class ProfileController extends Controller
{
    public function show()
    {
        $user = User::with('user_info')->findOrFail(Auth::id());
        $cartItems = Cart::with('products')
            ->where('user_id', $user->id)
            ->latest('id')
            ->take(5)
            ->get();

        $orderCount = Order::where('user_id', $user->id)
            ->where('deleted', 0)
            ->count();

        // Fetch hotel bookings for the user
        $bookings = Booking::with(['hotel', 'room'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $bookingCount = Booking::where('user_id', $user->id)->count();

        return view('front.profile-buyer', compact('user', 'cartItems', 'orderCount', 'bookings', 'bookingCount'));
    }

    public function edit()
    {
        $user = User::with('user_info')->findOrFail(Auth::id());
        return view('front.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'mobile' => 'required|string|min:10|max:15',
            'address' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->mobile = $validated['mobile'];
        $user->save();

        $profileImage = optional($user->user_info)->profile_image;

        if ($request->hasFile('profile_image')) {
            $uploadPath = public_path('uploads/profile');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image = $request->file('profile_image');
            $imageName = 'profile_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $imageName);

            if (!empty($profileImage)) {
                $oldImagePath = $uploadPath . DIRECTORY_SEPARATOR . $profileImage;
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }

            $profileImage = $imageName;
        }

        if ($request->filled('address') || optional($user->user_info)->address || $request->hasFile('profile_image')) {
            UserInfo::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'address' => $validated['address'] ?? ($user->user_info->address ?? ''),
                    'profile_image' => $profileImage,
                ]
            );
        }

        return redirect()->route('profile_buyer')->with('success', 'Profile updated successfully.');
    }

    public function sellerEdit()
    {
        $user = User::with('user_info')->findOrFail(Auth::id());

        if (!$user->hasRole('Seller')) {
            abort(403);
        }

        return view('home.seller_profile_edit', compact('user'));
    }

    public function sellerUpdate(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->hasRole('Seller')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'mobile' => 'required|string|min:10|max:15',
            'address' => 'nullable|string|max:500',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->mobile = $validated['mobile'];
        $user->save();

        if ($request->filled('address') || optional($user->user_info)->address) {
            UserInfo::updateOrCreate(
                ['user_id' => $user->id],
                ['address' => $validated['address'] ?? ($user->user_info->address ?? '')]
            );
        }

        return redirect()->route('seller.profile.edit')->with('success', 'Seller profile updated successfully.');
    }

    public function sellerKycEdit()
    {
        $user = User::with('kycVerification')->findOrFail(Auth::id());

        if (!$user->hasRole('Seller')) {
            abort(403);
        }

        $kyc = $user->kycVerification;

        return view('home.seller_kyc', compact('user', 'kyc'));
    }

    /**
     * Create a draft KYC record so Razorpay membership payment can reference it.
     */
    public function sellerKycCreateDraft(Request $request)
    {
        $user = User::with('kycVerification')->findOrFail(Auth::id());

        if (!$user->hasRole('Seller')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $kyc = $user->kycVerification;

        if (!$kyc) {
            $kyc = SellerKycVerification::create([
                'user_id' => $user->id,
                'status' => 'Draft',
            ]);
        }

        return response()->json(['kyc_id' => $kyc->id]);
    }

    public function sellerKycUpdate(Request $request)
    {
        $user = User::with('kycVerification')->findOrFail(Auth::id());

        if (!$user->hasRole('Seller')) {
            abort(403);
        }

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
            'membership_payment_screenshot_reference',
            'mobile_verified_at',
            'email_verified_at',
        ];

        foreach ($requiredColumns as $column) {
            if (!Schema::hasColumn('seller_kyc_verifications', $column)) {
                return back()->withInput()->withErrors([
                    'error' => 'KYC setup is incomplete. Please run database migrations before submitting KYC.',
                ]);
            }
        }

        if (empty(trim((string) $user->mobile)) || empty(trim((string) $user->email))) {
            return redirect()->route('seller.profile.edit')->withErrors([
                'mobile' => 'Mobile and email are mandatory for KYC. Please update your seller profile first.',
            ]);
        }

        // Determine if this is a Hotel & Resorts seller (category ID 4)
        $userInfo = $user->user_info;
        $isHotelCategory = $userInfo && $userInfo->business_category_id === 4;
        
        // Build validation rules — Shiprocket fields are optional for Hotel sellers
        $validationRules = [
            'legal_name' => 'required|string|max:190',
            'shiprocket_pickup_location' => $isHotelCategory ? 'nullable|string|max:255' : 'required|string|max:255',
            'shiprocket_pickup_id' => 'nullable|string|max:255',
            'pickup_address'   => $isHotelCategory ? 'nullable|string|max:300' : 'required|string|max:300',
            'pickup_address_2' => 'nullable|string|max:300',
            'pickup_city'      => $isHotelCategory ? 'nullable|string|max:100' : 'required|string|max:100',
            'pickup_state'     => $isHotelCategory ? 'nullable|string|max:100' : 'required|string|max:100',
            'pickup_pincode'   => $isHotelCategory ? 'nullable|digits:6' : 'required|digits:6',
            'pickup_phone'     => $isHotelCategory ? 'nullable|digits:10' : 'required|digits:10',
            'pickup_email'     => $isHotelCategory ? 'nullable|email|max:190' : 'required|email|max:190',
            'pan_number' => 'required|string|max:30',
            'aadhaar_number' => 'required|string|max:20',
            'bank_account_holder' => 'required|string|max:190',
            'bank_account_number' => 'required|string|max:50',
            'bank_ifsc_code' => 'required|string|max:20',
            'bank_name' => 'required|string|max:190',
            'gst_number' => 'required|string|max:20',
            'bank_passbook_file' => 'nullable|file|mimes:pdf,jpeg,jpg,png,webp|max:4096',
            'id_card_file' => 'nullable|file|mimes:pdf,jpeg,jpg,png,webp|max:4096',
            'pan_card_file' => 'nullable|file|mimes:pdf,jpeg,jpg,png,webp|max:4096',
            'gst_certificate_file' => 'nullable|file|mimes:pdf,jpeg,jpg,png,webp|max:4096',
            'payment_screenshot_file' => 'nullable|file|mimes:jpeg,jpg,png,webp|max:4096',
        ];
        
        $validated = $request->validate($validationRules);

        $kyc = SellerKycVerification::firstOrNew(['user_id' => $user->id]);

        $bankPassbookReference = (string) ($kyc->bank_passbook_reference ?? '');
        $idCardReference = (string) ($kyc->id_card_reference ?? '');
        $panCardReference = (string) ($kyc->pan_card_reference ?? '');
        $gstCertificateReference = (string) ($kyc->gst_certificate_reference ?? '');

        $uploadedBankPassbook = $this->uploadSellerKycFile(
            $request,
            'bank_passbook_file',
            'uploads/seller-kyc/bank-passbook',
            $user->id,
            $bankPassbookReference
        );
        if (!empty($uploadedBankPassbook)) {
            $bankPassbookReference = $uploadedBankPassbook;
        }

        $uploadedIdCard = $this->uploadSellerKycFile(
            $request,
            'id_card_file',
            'uploads/seller-kyc/id-card',
            $user->id,
            $idCardReference
        );
        if (!empty($uploadedIdCard)) {
            $idCardReference = $uploadedIdCard;
        }

        $uploadedPanCard = $this->uploadSellerKycFile(
            $request,
            'pan_card_file',
            'uploads/seller-kyc/pan-card',
            $user->id,
            $panCardReference
        );
        if (!empty($uploadedPanCard)) {
            $panCardReference = $uploadedPanCard;
        }

        $uploadedGstCertificate = $this->uploadSellerKycFile(
            $request,
            'gst_certificate_file',
            'uploads/seller-kyc/gst-certificate',
            $user->id,
            $gstCertificateReference
        );
        if (!empty($uploadedGstCertificate)) {
            $gstCertificateReference = $uploadedGstCertificate;
        }

        $paymentScreenshotReference = (string) ($kyc->membership_payment_screenshot_reference ?? '');
        $uploadedPaymentScreenshot = $this->uploadSellerKycFile(
            $request,
            'payment_screenshot_file',
            'uploads/seller-kyc/payment-screenshots',
            $user->id,
            $paymentScreenshotReference
        );
        if (!empty($uploadedPaymentScreenshot)) {
            $paymentScreenshotReference = $uploadedPaymentScreenshot;
        }

        if (empty(trim((string) $bankPassbookReference))) {
            return back()->withInput()->withErrors([
                'bank_passbook_file' => 'Front page of bank passbook is mandatory.',
            ]);
        }

        if (empty(trim((string) $idCardReference))) {
            return back()->withInput()->withErrors([
                'id_card_file' => 'ID card document is mandatory.',
            ]);
        }

        if (empty(trim((string) $panCardReference))) {
            return back()->withInput()->withErrors([
                'pan_card_file' => 'PAN card document is mandatory.',
            ]);
        }

        if (empty(trim((string) $gstCertificateReference))) {
            return back()->withInput()->withErrors([
                'gst_certificate_file' => 'GST registration certificate is mandatory.',
            ]);
        }

        $isResubmission = $kyc->exists;

        $kyc->legal_name = trim($validated['legal_name']);

        // If the pickup location name or address changed, reset sync so it gets re-registered
        $newPickupLocation = trim($validated['shiprocket_pickup_location'] ?? '');
        $newPickupAddress = trim($validated['pickup_address'] ?? '');
        $pickupChanged = $kyc->shiprocket_pickup_location !== $newPickupLocation
            || $kyc->pickup_address !== $newPickupAddress;

        $kyc->shiprocket_pickup_location = $newPickupLocation;
        $kyc->shiprocket_pickup_id = $validated['shiprocket_pickup_id'] ?? null;
        $kyc->pickup_address   = $newPickupAddress;
        $kyc->pickup_address_2 = trim($validated['pickup_address_2'] ?? '');
        $kyc->pickup_city      = trim($validated['pickup_city'] ?? '');
        $kyc->pickup_state     = trim($validated['pickup_state'] ?? '');
        $kyc->pickup_pincode   = $validated['pickup_pincode'] ?? null;
        $kyc->pickup_phone     = $validated['pickup_phone'] ?? null;
        $kyc->pickup_email     = trim($validated['pickup_email'] ?? '');
        if ($pickupChanged) {
            $kyc->pickup_sync_status = null;
            $kyc->pickup_synced_at   = null;
        }
        $kyc->pan_number = strtoupper(trim($validated['pan_number']));
        $kyc->aadhaar_number = preg_replace('/\s+/', '', trim($validated['aadhaar_number']));
        $kyc->bank_account_holder = trim($validated['bank_account_holder']);
        $kyc->bank_account_number = preg_replace('/\s+/', '', trim($validated['bank_account_number']));
        $kyc->bank_ifsc_code = strtoupper(trim($validated['bank_ifsc_code']));
        $kyc->bank_name = trim($validated['bank_name']);
        $kyc->gst_number = strtoupper(trim($validated['gst_number']));
        $kyc->bank_passbook_reference = $bankPassbookReference;
        $kyc->id_card_reference = $idCardReference;
        $kyc->pan_card_reference = $panCardReference;
        $kyc->gst_certificate_reference = $gstCertificateReference;
        $kyc->membership_payment_screenshot_reference = $paymentScreenshotReference;
        $kyc->id_type = 'KYC_V2_DOCS';
        $kyc->id_number = $kyc->pan_number . ' / ' . $kyc->aadhaar_number;
        $kyc->document_reference = $kyc->bank_passbook_reference;
        $kyc->status = 'Pending';
        $kyc->admin_note = null;
        $kyc->verified_by = null;
        $kyc->verified_at = null;
        $kyc->mobile_verified_at = null;
        $kyc->email_verified_at = null;
        $kyc->save();

        AdminNotification::notify(
            AdminNotification::TYPE_KYC_SUBMITTED,
            $isResubmission ? 'Seller KYC Re-Submitted' : 'Seller KYC Submitted',
            'Seller "' . $user->name . '" submitted KYC details for verification.',
            [
                'link' => route('admin.seller_kyc.index'),
                'related_id' => $user->id,
                'related_type' => User::class,
            ]
        );

        // Email: seller gets confirmation
        try {
            Mail::to($user->email)->send(new SellerKycSubmittedNotification($user, $isResubmission));
        } catch (\Exception $e) {
            \Log::error('Failed to send KYC submitted email to seller: ' . $e->getMessage());
        }

        // Email: admin gets alert
        $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
        if ($adminEmail) {
            try {
                $kyc->load('seller');
                Mail::to($adminEmail)->send(new AdminKycSubmittedNotification($user, $kyc, $isResubmission));
            } catch (\Exception $e) {
                \Log::error('Failed to send KYC submitted admin email: ' . $e->getMessage());
            }
        }

        return redirect()->route('seller.kyc.edit')->with('success', 'KYC details submitted successfully. Admin verification is pending.');
    }

    private function uploadSellerKycFile(Request $request, string $inputName, string $directory, int $userId, string $existingReference = ''): ?string
    {
        if (!$request->hasFile($inputName)) {
            return null;
        }

        $uploadPath = public_path($directory);
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file = $request->file($inputName);
        $fileName = $inputName . '_' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadPath, $fileName);

        if (!empty($existingReference) && Str::startsWith($existingReference, $directory . '/')) {
            $oldFilePath = public_path($existingReference);
            if (file_exists($oldFilePath)) {
                @unlink($oldFilePath);
            }
        }

        return $directory . '/' . $fileName;
    }

    public function addresses()
    {
        $user = User::with('user_info')->findOrFail(Auth::id());
        return view('front.profile.addresses', compact('user'));
    }

    public function updateAddress(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'phone' => 'required|string|min:10|max:15',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|min:6|max:10',
        ]);

        // Build full address string for backward compatibility
        $fullAddress = implode(', ', array_filter([
            $validated['address_line_1'],
            $validated['address_line_2'],
            $validated['landmark'] ? 'Near ' . $validated['landmark'] : null,
            $validated['city'],
            $validated['state'],
            'PIN: ' . $validated['pincode'],
        ]));

        UserInfo::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'address' => $fullAddress,
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'address_line_1' => $validated['address_line_1'],
                'address_line_2' => $validated['address_line_2'],
                'landmark' => $validated['landmark'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'pincode' => $validated['pincode'],
            ]
        );

        return back()->with('success', 'Address updated successfully.');
    }

    public function downloadSellerGuide()
    {
        if (!Auth::user()->hasRole('Seller')) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdf.seller_guide');
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('WinkelKart_Seller_Guide.pdf');
    }
}
