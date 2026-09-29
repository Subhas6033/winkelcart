<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api as RazorpayApi;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Business types for seller registration
        $categories = [
            ['id' => 1, 'name' => 'Electronics'],
            ['id' => 2, 'name' => 'Mobiles'],
            ['id' => 3, 'name' => 'Fashion'],
            ['id' => 5, 'name' => 'Appliances'],
            ['id' => 4, 'name' => 'Hotels Resorts'],
        ];
        // All Indian states ordered by name
        $states = DB::table('states')->where('status', '1')->orderBy('name')->get();
        return view("front.register-buisness", compact('categories', 'states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Display registration form for new sellers
        $categories = [
            ['id' => 1, 'name' => 'Electronics'],
            ['id' => 2, 'name' => 'Mobiles'],
            ['id' => 3, 'name' => 'Fashion'],
            ['id' => 5, 'name' => 'Appliances'],
            ['id' => 4, 'name' => 'Hotels Resorts'],
        ];
        $states = DB::table('states')->where('status', '1')->orderBy('name')->get();
        return view("front.register-buisness", compact('categories', 'states'));
    }
    
    /**
     * Show simple payment page to upgrade to Pro plan (for authenticated sellers)
     * GET /seller/upgrade-to-pro
     */
    public function upgradeToProPlan(Request $request)
    {
        // Check if user is authenticated and is a seller
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        if (!$user->hasRole('Seller')) {
            return redirect()->route('index')->with('error', 'Only sellers can upgrade.');
        }
        
        // Check if already on pro plan
        $userInfo = DB::table('user_info')->where('user_id', $user->id)->first();
        if ($userInfo && $userInfo->subscription_plan === 'paid') {
            return redirect()->route('seller-dashboard')->with('info', 'You are already on the Pro plan.');
        }
        
        // Create Razorpay order for upgrade
        try {
            $key    = config('services.razorpay.key');
            $secret = config('services.razorpay.secret');
            
            if (!$key || !$secret) {
                return redirect()->back()->withErrors(['error' => 'Payment gateway not configured. Please contact support.']);
            }
            
            $api = new RazorpayApi($key, $secret);
            $order = $api->order->create([
                'receipt'  => 'seller_upgrade_' . $user->id . '_' . time(),
                'amount'   => 99900,   // ₹999 in paise
                'currency' => 'INR',
                'notes'    => ['type' => 'seller_upgrade', 'user_id' => $user->id],
            ]);
            
            // Show simple payment view
            return view('front.upgrade-pro-payment', [
                'orderId' => $order->id,
                'amount' => 99900,
                'currency' => 'INR',
                'razorpayKey' => $key,
                'userName' => $user->name,
                'userEmail' => $user->email,
                'userPhone' => $user->mobile,
            ]);
        } catch (\Exception $e) {
            Log::error('Upgrade payment order creation failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Could not create payment order. Please try again.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->input('g-recaptcha-response'));
        $rules = [
            'name'       => 'required|string|max:255',
            'address'    => 'required|string',
            // 'mobile'     => 'required|string|max:10|min:10',
            'mobile'     => [
                'required',
                'string',
                'max:10',
                'min:10',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('deleted', 0);
                }),
            ],
            'email'      => [
                'required',
                'email',
                'max:50',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('deleted', 0);
                }),
            ],
            'business_category_id'=> 'required|in:1,2,3,4,5',
            'gst_no'     => 'required|string|max:20',
            'district'   => 'required|string|max:100',
            'state_id'   => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
            'subscription_plan' => 'required|in:free,paid',
        ];

        if ($request->subscription_plan === 'paid') {
            $rules['razorpay_payment_id'] = 'required|string';
            $rules['razorpay_order_id']   = 'required|string';
            $rules['razorpay_signature']  = 'nullable|string'; // Make signature optional
        }

        // reCAPTCHA validation and verification disabled for local/testing
        $request->validate($rules);

        // Verify Razorpay payment for paid plan using Razorpay API
        if ($request->subscription_plan === 'paid') {
            try {
                $key    = config('services.razorpay.key');
                $secret = config('services.razorpay.secret');
                
                if (!$key || !$secret) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['error' => 'Payment gateway not configured. Please contact support.']);
                }
                
                $api = new RazorpayApi($key, $secret);
                
                // Fetch payment details from Razorpay API to verify
                $payment = $api->payment->fetch($request->razorpay_payment_id);
                
                // Verify payment details
                if (!$payment) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['error' => 'Payment verification failed. Payment not found. Please try again.']);
                }
                
                // Check if payment is captured (successful)
                if ($payment->status !== 'captured') {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['error' => 'Payment was not completed successfully. Status: ' . $payment->status . '. Please try again.']);
                }
                
                // Verify order ID matches
                if ($payment->order_id !== $request->razorpay_order_id) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['error' => 'Order mismatch in payment verification. Please try again.']);
                }
                
                // Verify amount (should be ₹999 = 99900 paise)
                if ($payment->amount !== 99900) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['error' => 'Payment amount mismatch. Expected ₹999. Please try again.']);
                }
                
            } catch (\Exception $e) {
                Log::error('Razorpay payment verification failed: ' . $e->getMessage());
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Payment verification failed: ' . $e->getMessage()]);
            }
        }

        DB::beginTransaction();

        // print_r($request->all());die;
        try {
            // Step 1: create user
            $password = Str::random(8);

            $user = User::create([
                'name'     => $request->name,
                'mobile'    => $request->mobile,
                'email'    => $request->email,
                'password' => Hash::make($password),
                'deleted'  => 0,
            ]);

            // Step 2: create user info
            DB::table('user_info')->insert([
                'user_id'    => $user->id,
                'address'    => $request->address,
                'business_category_id'=> $request->business_category_id,
                'gst_no'=> $request->gst_no,
                'city' => $request->district,
                'state_id'   => $request->state_id,
                'country_id' => $request->country_id,
                'subscription_plan' => $request->subscription_plan,
                'registration_razorpay_payment_id' => $request->subscription_plan === 'paid' ? $request->razorpay_payment_id : null,
                'registration_razorpay_order_id'   => $request->subscription_plan === 'paid' ? $request->razorpay_order_id : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Step 3: assign role (if using spatie/laravel-permission)
            // $user->assignRole($request->input('roles'));
            $user->assignRole('Seller'); // 17 is seller role id

            DB::commit();

            $businessTypeMap = [
                1 => 'Electronics',
                2 => 'Mobiles',
                3 => 'Fashion',
                4 => 'Hotels Resorts',
                5 => 'Appliances',
            ];
            $businessType = $businessTypeMap[(int) $request->business_category_id] ?? 'Electronics';
            $state = DB::table('states')->where('id', $request->state_id)->value('name');
            $country = DB::table('countries')->where('id', $request->country_id)->value('name');
            // Step 4: send email
            $data = [
                'email' => $request->email,
                'password' => $password,
                'name' => $request->name,
                'business' => $request->name,
                'address' => $request->address,
                'phone' => $request->mobile,
                'businessType' => $businessType,
                'district' => $request->district,
                'state' => $state,
                'country' => $country,
            ];
            Helper::seller_register_email($data);
            Helper::admin_seller_register_email($data);

            // Create admin notification for seller registration
            AdminNotification::notify(
                AdminNotification::TYPE_SELLER_REGISTERED,
                'New Seller Registered',
                'A new seller "' . $request->name . '" has registered with email ' . $request->email,
                [
                    'link' => route('users.index'),
                    'related_id' => $user->id,
                    'related_type' => User::class,
                ]
            );

            return redirect()->back()->with('success', 'Business registered successfully! Temporary password: '.$password);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Complete the pro plan upgrade after successful payment verification
     * POST /seller/complete-upgrade
     */
    public function completeProUpgrade(Request $request)
    {
        // Check if user is authenticated and is a seller
        if (!auth()->check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $user = auth()->user();
        if (!$user->hasRole('Seller')) {
            return response()->json(['error' => 'Only sellers can upgrade'], 403);
        }
        
        // Validate payment details
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
        ]);
        
        // Verify payment with Razorpay API
        try {
            $key    = config('services.razorpay.key');
            $secret = config('services.razorpay.secret');
            
            if (!$key || !$secret) {
                return response()->json(['error' => 'Payment gateway not configured'], 503);
            }
            
            $api = new RazorpayApi($key, $secret);
            $payment = $api->payment->fetch($request->razorpay_payment_id);
            
            // Verify payment details
            if (!$payment) {
                return response()->json(['error' => 'Payment verification failed. Payment not found.'], 400);
            }
            
            if ($payment->status !== 'captured') {
                return response()->json(['error' => 'Payment was not completed successfully. Status: ' . $payment->status], 400);
            }
            
            if ($payment->order_id !== $request->razorpay_order_id) {
                return response()->json(['error' => 'Order mismatch in payment verification.'], 400);
            }
            
            if ($payment->amount !== 99900) {
                return response()->json(['error' => 'Payment amount mismatch. Expected ₹999.'], 400);
            }
            
            // Update user subscription to paid
            DB::table('user_info')
                ->where('user_id', $user->id)
                ->update([
                    'subscription_plan' => 'paid',
                    'registration_razorpay_payment_id' => $request->razorpay_payment_id,
                    'registration_razorpay_order_id'   => $request->razorpay_order_id,
                    'updated_at' => now(),
                ]);
            
            Log::info('Seller upgrade successful: User ID ' . $user->id . ', Payment ID ' . $request->razorpay_payment_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Successfully upgraded to Pro Plan!',
                'redirect' => route('seller-dashboard'),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Seller upgrade verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment verification failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create a Razorpay order for Pro plan registration payment (no auth required).
     * POST /register-buisness/create-payment
     */
    public function createRegistrationPayment(Request $request)
    {
        $key    = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');

        if (!$key || !$secret) {
            return response()->json(['error' => 'Payment gateway not configured. Please contact support.'], 503);
        }

        try {
            $api   = new RazorpayApi($key, $secret);
            $order = $api->order->create([
                'receipt'  => 'seller_reg_' . time(),
                'amount'   => 99900,   // ₹999 in paise
                'currency' => 'INR',
                'notes'    => ['type' => 'seller_registration'],
            ]);

            return response()->json([
                'order_id'    => $order->id,
                'amount'      => 99900,
                'currency'    => 'INR',
                'key'         => $key,
                'name'        => 'WinkelKart',
                'description' => 'Pro Seller Subscription — ₹999/year',
            ]);
        } catch (\Exception $e) {
            Log::error('Registration payment order creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create payment order. Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function register_buyer(){        
        return view("front.register-buyer");
    }
    public function buyer_store(Request $request){
        // $password = Str::random(8);
        // $data = [
        //         'email' => $request->email,
        //         'password' => $password,
        //         'name' => $request->name,
        //         'phone' => $request->mobile,
        //     ];
        // Helper::user_register_email($data);
        // return response()->json(['status' => 'called']);
        $request->validate([
            'name'       => 'required|string|max:255',
            'mobile'     => [
                            'required',                            
                            'string',
                            'max:10',
                            'min:10',
                            Rule::unique('users')->where(function ($query) {
                                return $query->where('deleted', 0);
                            }),
                        ],
            'email'      => [
                            'required',
                            'email',
                            'max:255',
                            Rule::unique('users')->where(function ($query) {
                                return $query->where('deleted', 0);
                            }),
                        ],
        ]);
        // reCAPTCHA disabled
        DB::beginTransaction();

        try {
            // Step 1: create user
            $password = Str::random(8);

            $user = User::create([
                'name'     => $request->name,
                'mobile'    => $request->mobile,
                'email'    => $request->email,
                'password' => Hash::make($password),
                'deleted'  => 0,
            ]);

            // Step 2: create user info (empty - user will add address later)
            DB::table('user_info')->insert([
                'user_id'    => $user->id,
                'address'    => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Step 3: assign role (if using spatie/laravel-permission)
            // $user->assignRole($request->input('roles'));
            $user->assignRole('User'); // 18 is user role id

            DB::commit();

            // Step 4: send email
            $data = [
                'email' => $request->email,
                'password' => $password,
                'name' => $request->name,
                'phone' => $request->mobile,
            ];
            Helper::user_register_email($data);
            Helper::admin_buyer_register_email($data);

            // Create admin notification for buyer registration
            AdminNotification::notify(
                AdminNotification::TYPE_USER_REGISTERED,
                'New Buyer Registered',
                'A new buyer "' . $request->name . '" has registered with email ' . $request->email,
                [
                    'link' => route('users.index'),
                    'related_id' => $user->id,
                    'related_type' => User::class,
                ]
            );

            return redirect()->back()->with('success', 'Registered successfully! Temporary password: '.$password);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
