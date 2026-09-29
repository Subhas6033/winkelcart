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
use PHPUnit\TextUI\Help;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = DB::table('categories')->whereIn('id', [1,2,3,4])->where(['status'=>'0', 'deleted'=>'0'])->get();
        $states = DB::table('states')->where(['status'=>'1'])->get();        
        $countries = DB::table('countries')->where(['status'=>'1'])->get();
        return view("front.register-buisness", compact('categories', 'states', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $request->validate([
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
            'business_category_id'=> 'required|exists:categories,id',
            'gst_no'     => 'required|string|max:20',
            'state_id'   => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
            'g-recaptcha-response' => 'required',            
        ]);

        // Verify Google reCAPTCHA
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        $responseData = $response->json();

        // if (!$responseData['success']) {
        //     return back()->withErrors(['g-recaptcha-response' => 'Captcha verification failed. Please try again.'])->withInput();
        // }

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
                'state_id'   => $request->state_id,
                'country_id' => $request->country_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Step 3: assign role (if using spatie/laravel-permission)
            // $user->assignRole($request->input('roles'));
            $user->assignRole('Seller'); // 17 is seller role id

            DB::commit();

            $businessType = DB::table('categories')->where('id', $request->business_category_id)->value('name');
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
                'state' => $state,
                'country' => $country,
            ];
            Helper::seller_register_email($data);
            Helper::admin_seller_register_email($data);

            return redirect()->back()->with('success', 'Business registered successfully! Temporary password: '.$password);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
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
        $request->validate([
            'name'       => 'required|string|max:255',
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
                            'max:255',
                            Rule::unique('users')->where(function ($query) {
                                return $query->where('deleted', 0);
                            }),
                        ],
            'address'    => 'required|string',
            'g-recaptcha-response' => 'required',
        ]);

        // Verify Google reCAPTCHA
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        $responseData = $response->json();

        // if (!$responseData['success']) {
        //     return back()->withErrors(['g-recaptcha-response' => 'Captcha verification failed. Please try again.'])->withInput();
        // }
        // print_r($_REQUEST);die;
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

            // Step 2: create user info
            DB::table('user_info')->insert([
                'user_id'    => $user->id,
                'address'    => $request->address,
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

            return redirect()->back()->with('success', 'Registered successfully! Temporary password: '.$password);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
