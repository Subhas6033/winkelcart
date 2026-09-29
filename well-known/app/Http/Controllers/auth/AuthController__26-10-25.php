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
            $role_name = $user->getRoleNames()->first();
            if ($role_name == 'Seller' && $user->is_seller == 0) {
                Auth::logout(); // logout immediately
                return redirect()->route('login')->with('error_login', 'Admin has not approved your account yet.');
            }

            // Seller is approved

            $users = User::where('status', 0)->where('deleted', 0)->get();
            $orders = Order::where('status', 0)->where('deleted', 0)->get();
            $orderCount = $orders->count();

            $sellerCount = 0;
            $userCount = 0;
            foreach ($users as $user) {
                $role_name_chk = $user->getRoleNames()->first();
                if ($role_name_chk == 'Seller') {
                    $sellerCount++;
                } elseif ($role_name_chk == 'User') {
                    $userCount++;
                }
            }
            // echo $role_name;die;
            if($role_name == 'User'){
                return redirect("/");
            }
            return view('home.index', compact('sellerCount', 'userCount', 'orderCount'));
            // return redirect("admin/dashboard", compact('sellerCount', 'userCount'));
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
}
