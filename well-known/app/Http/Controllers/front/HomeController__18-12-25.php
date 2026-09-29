<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        $top_deals = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(6);
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 5
            ])->firstOrFail();

        $electronics = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(6);
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 1
            ])->firstOrFail();

        $computers = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(6);
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 6
            ])->firstOrFail();

        $monitor_mouse = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(6);
            }])->where([
                'deleted' => '0',
                'status'  => '0',
            ])
            ->whereIn('id', [7,8])
            ->get();

        $electronics_more = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(10);
            }])->where([
                'deleted' => '0',
                'status'  => '0',
            ])
            ->whereIn('id', [1,2,5,6,7,8,9,10,11,12])
            ->get();

        $hotels_resort = Category::with(['products' => function ($query) {
            $query->orderBy('id', 'desc');
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 4
            ])->firstOrFail();

        // print_r($electronics_more->toArray());die;

        return view("front.index", compact('top_deals', 'electronics', 'computers', 'monitor_mouse', 'electronics_more', 'hotels_resort'));
    }

    public function computers(){
        $elevates = Product::with('category')->where([
                'deleted' => '0',
                'status'  => '0',
            ])->take(10)->orderBy('id', 'desc')->get();

        $desktops = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(12);
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 6
            ])->firstOrFail();

        $monitors = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(6);
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 7
            ])->firstOrFail();

        $mouses = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(6);
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 8
            ])->firstOrFail();
        // print_r($elevates->toArray());die;

        return view("front.computers", compact('elevates', 'desktops', 'monitors', 'mouses'));
    }

    public function electronics(){
        $electronics1 = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(7);
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 1
            ])->firstOrFail();

        $electronics2 = Category::with(['products' => function ($query) {
            $query->orderBy('id', 'desc');
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 11
            ])->firstOrFail();

        $mobile_accessories = Category::with(['products' => function ($query) {
            $query->orderBy('id', 'desc');
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 12
            ])->firstOrFail();
            
        $headphones = Category::with(['products' => function ($query) {
            $query->orderBy('id', 'desc');
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 11
            ])->firstOrFail();

        // print_r($electronics1->toArray());die;

        return view("front.electronics", compact('electronics1', 'electronics2', 'mobile_accessories', 'headphones'));
    }

    public function groceries(){
        $coocking_oil = Category::with(['products' => function ($query) {
                $query->orderBy('id', 'desc')->take(7);
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 13
            ])->firstOrFail();

        $rice_grains = Category::with(['products' => function ($query) {
            $query->orderBy('id', 'desc');
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 14
            ])->firstOrFail();

        $spices_masala = Category::with(['products' => function ($query) {
            $query->orderBy('id', 'desc');
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 15
            ])->firstOrFail();
            
        $snacks_buiscuits = Category::with(['products' => function ($query) {
            $query->orderBy('id', 'desc');
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 16
            ])->firstOrFail();

        // print_r($electronics1->toArray());die;

        return view("front.groceries", compact('coocking_oil', 'rice_grains', 'spices_masala', 'snacks_buiscuits'));
    }

    public function hotels_resorts(){
        $hotels_banner = Category::with(['products' => function ($query) {
            $query->orderBy('id', 'desc')->take(3);
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 4
            ])->firstOrFail();

        $hotels_resort = Category::with(['products' => function ($query) {
            $query->orderBy('id', 'desc');
            }])->where([
                'deleted' => '0',
                'status'  => '0',
                'id'      => 4
            ])->firstOrFail();

        // print_r($hotels_resort->toArray());die;                
        return view("front.hotels-resort", compact('hotels_banner', 'hotels_resort'));
    }

    public function register_buisness(){        
        $categories = DB::table('categories')->where(['status'=>'0', 'deleted'=>'0'])->get();
        $states = DB::table('states')->where(['status'=>'1'])->get();        
        $countries = DB::table('countries')->where(['status'=>'1'])->get();
        return view("front.register-buisness", compact('categories', 'states', 'countries'));
    }
}
