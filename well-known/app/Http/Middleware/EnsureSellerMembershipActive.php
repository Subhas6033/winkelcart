<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EnsureSellerMembershipActive
{
    /**
     * Sellers must have payment_status == 'Verified' AND membership_expiry >= now().
     * Admins and buyers pass through unaffected.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        if (!$user->hasRole('Seller')) {
            return $next($request);
        }

        $kyc = $user->kycVerification;

        $membershipActive = $kyc
            && $kyc->payment_status === 'Verified'
            && $kyc->membership_expiry
            && Carbon::parse($kyc->membership_expiry)->isFuture();

        if (!$membershipActive) {
            $message = 'Your seller membership is inactive or expired. Please pay the ₹999 membership fee to continue.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'membership_required' => true,
                ], 403);
            }

            return redirect()->route('seller.kyc.edit')->with('error', $message);
        }

        return $next($request);
    }
}
