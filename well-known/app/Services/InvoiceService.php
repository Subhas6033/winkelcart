<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    /**
     * Generate invoice PDF for an order
     *
     * @param Order $order
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateInvoicePdf(Order $order)
    {
        $order->load(['items.product.seller', 'buyer.user_info']);
        
        $data = [
            'order' => $order,
            'buyer' => $order->buyer,
            'orderItems' => $order->items,
        ];
        
        $pdf = Pdf::loadView('invoices.order-invoice', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }
    
    /**
     * Generate and save invoice PDF to storage
     *
     * @param Order $order
     * @return string Path to the saved file
     */
    public function saveInvoicePdf(Order $order)
    {
        $pdf = $this->generateInvoicePdf($order);
        
        $filename = 'invoice_' . $order->order_number . '.pdf';
        $path = 'invoices/' . $filename;
        
        // Ensure the directory exists
        if (!file_exists(public_path('uploads/invoices'))) {
            mkdir(public_path('uploads/invoices'), 0755, true);
        }
        
        $fullPath = public_path('uploads/invoices/' . $filename);
        $pdf->save($fullPath);
        
        return $fullPath;
    }
    
    /**
     * Get invoice PDF as raw output (for download or attachment)
     *
     * @param Order $order
     * @return string
     */
    public function getInvoicePdfOutput(Order $order)
    {
        $pdf = $this->generateInvoicePdf($order);
        return $pdf->output();
    }
    
    /**
     * Get invoice filename
     *
     * @param Order $order
     * @return string
     */
    public function getInvoiceFilename(Order $order)
    {
        return 'WinkelKart_Invoice_' . $order->order_number . '.pdf';
    }
}
