<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    private function renderManagedPage($slug, $fallbackView)
    {
        $page = CmsPage::where('slug', $slug)
            ->where('is_active', 1)
            ->first();

        if ($page) {
            return view('front.pages.cms', compact('page'));
        }

        return view($fallbackView);
    }

    public function terms()
    {
        return $this->renderManagedPage('terms-and-conditions', 'front.pages.terms');
    }

    public function privacy()
    {
        return $this->renderManagedPage('privacy-policy', 'front.pages.privacy');
    }

    public function returnRefund()
    {
        return $this->renderManagedPage('return-refund-policy', 'front.pages.return-refund');
    }

    public function shippingDelivery()
    {
        return $this->renderManagedPage('shipping-delivery-policy', 'front.pages.shipping-delivery');
    }

    public function cancellation()
    {
        return $this->renderManagedPage('cancellation-policy', 'front.pages.cancellation');
    }

    public function orderSuccess(Request $request, $orderNumber = null)
    {
        $order = null;

        if (Auth::check()) {
            $lookupNumber = $orderNumber ?: $request->query('order_number');
            if (!empty($lookupNumber)) {
                $order = Order::where('user_id', Auth::id())
                    ->where('order_number', $lookupNumber)
                    ->where('deleted', 0)
                    ->first();
            }
        }

        return view('front.pages.order-success', compact('order'));
    }

    public function paymentFailed()
    {
        return view('front.pages.payment-failed');
    }
}
