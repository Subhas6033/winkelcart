<?php

namespace App\Http\Controllers\auth;

use Hash;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginNotification;
use App\Mail\PasswordResetNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;


class AuthController extends Controller
{
    //
    function __construct()
    {
    }

    public function register(Request $request)
    {

        $validatedData = $request->validate([
            "email" => "required",
            "name" => "required",
            "password" => "required"
        ], [
            "email.required" => "Email is required"
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // echo "Registered";

        return redirect("register")->with("success", "Registered successfully!");
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            "email" => "required",
            "password" => "required"
        ], [
            "email.required" => "Email is required",
            "password.required" => "Password is required"
        ]);
        $credentials = $request->only("email", "password");
        $credentials['status'] = 0;
        $credentials['deleted'] = 0;
        //echo "<pre>";print_r($credentials);die;
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $role = $user->getRoleNames()->first();

            if ($role == 'Seller' && $user->is_seller == 0) {
                Auth::logout(); // logout immediately
                return redirect()->route('login')->with('error_login', 'Admin has not approved your account yet.');
            }

            // default values
            $sellerCount = 0;
            $userCount = 0;
            $orderCount = 0;
            $totalSales = 0;
            $totalReceivedAmount = 0;

            // $users = User::where('status', 0)->where('deleted', 0)->get();
            // $orders = Order::where('status', 0)->where('deleted', 0)->get();
            // $orderCount = $orders->count();
            // $totalSales = $orders->sum('total_amount');

            // $sellerCount = 0;
            // $userCount = 0;
            // foreach ($users as $user) {
            //     $role_name_chk = $user->getRoleNames()->first();
            //     if ($role_name_chk == 'Seller') {
            //         $sellerCount++;
            //     } elseif ($role_name_chk == 'User') {
            //         $userCount++;
            //     }
            // }
            // echo $role;die;
            // return view('home.index', compact('sellerCount', 'userCount', 'orderCount', 'totalSales'));

            if ($role === 'Admin') {
                // --- Admin Dashboard ---
                $users = User::where('status', 0)->where('deleted', 0)->get();
                $orders = Order::where('status', 0)->where('deleted', 0)->get();

                $orderCount = $orders->count();
                $totalSales = $orders->sum('total_amount');
                $totalReceivedAmount = $totalSales;

                foreach ($users as $user) {
                    $role_name_chk = $user->getRoleNames()->first();
                    if ($role_name_chk == 'Seller') {
                        $sellerCount++;
                    } elseif ($role_name_chk == 'User') {
                        $userCount++;
                    }
                }
            } 
            elseif ($role === 'Seller') {
                // --- Seller Dashboard ---
                $sellerId = $user->id;

                // Fetch only orders that have products by this seller
                $orders = Order::whereHas('items.product', function($q) use ($sellerId) {
                        $q->where('created_by', $sellerId);
                    })
                    ->where('status', 0)
                    ->where('deleted', 0)
                    ->with(['items.product'])
                    ->get();

                // Count unique orders for this seller
                $orderCount = $orders->count();

                // Calculate total sales only for seller's own items
                $totalSales = 0;
                $totalReceivedAmount = 0;

                foreach ($orders as $order) {
                    foreach ($order->items as $item) {
                        if ($item->product->created_by == $sellerId) {
                            $totalSales += $item->price * $item->quantity;
                            $totalReceivedAmount += $item->admin_paid_amount ?? 0; // safe null check
                        }
                    }
                }
            }
            if($role == 'User'){
                // Send login notification email to user
                try {
                    $currentUser = Auth::user();
                    Mail::to($currentUser->email)->send(new LoginNotification(
                        $currentUser,
                        $request->ip(),
                        $request->userAgent()
                    ));
                } catch (\Exception $e) {
                    // Log error but don't stop user from logging in
                    \Log::error('Login notification email failed: ' . $e->getMessage());
                }
                return redirect("/");
            }
            // Redirect Admin and Seller to the improved dashboard
            return redirect("admin/dashboard");
            
            
            
        }
        //return redirect("login");
        return redirect()->route('login')->with('error_login', 'Invalid Credentials!');
    }

    public function logout(Request $request)
    {

        Session::flush();
        Auth::logout();
        return redirect("login");
    }
    public function login_by_share_link($share_id)
    {
        Session::flush();
        Auth::logout();
        if (!empty($share_id)) {
            $get_customer_id = Customer::where("share_id", $share_id)->get();
            if (!empty($get_customer_id[0]->id)) {
                $get_user_info = User::where("module_id", $get_customer_id[0]->id)->where('related_module','customer')->get();
                if (!empty($get_user_info[0]->id)) {
                    Auth::loginUsingId($get_user_info[0]->id, TRUE);
                    return redirect("admin/dashboard");
                }
            }
        }
        return redirect()->route('login')->with('error_login', 'Invalid Credentials!');
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link to email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'This email address is not registered with us.'
        ]);

        // Delete any existing reset tokens for this email
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Generate a new token
        $token = Str::random(64);

        // Insert the token into the password_resets table
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Get the user
        $user = User::where('email', $request->email)->first();

        // Send the password reset email
        try {
            Mail::to($request->email)->send(new PasswordResetNotification($user, $token));
        } catch (\Exception $e) {
            \Log::error('Password reset email failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send reset email. Please try again later.');
        }

        return back()->with('success', 'We have sent a password reset link to your email address.');
    }

    /**
     * Show the password reset form
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset the user's password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'This email address is not registered.',
            'password.required' => 'Please enter a new password.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.'
        ]);

        // Get the password reset record
        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'No password reset request found for this email.']);
        }

        // Check if token is valid
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Invalid or expired password reset token.']);
        }

        // Check if token is not expired (valid for 60 minutes)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Password reset link has expired. Please request a new one.']);
        }

        // Update the user's password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete the reset token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. Please login with your new password.');
    }
}
