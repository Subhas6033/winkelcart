<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['product', 'user'])->orderBy('created_at', 'desc')->paginate(25);
        return view('reviews.index', compact('reviews'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully');
    }
}
