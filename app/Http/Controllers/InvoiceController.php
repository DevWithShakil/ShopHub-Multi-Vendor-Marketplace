<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download($id)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $isPaid = in_array($order->status, ['delivered', 'completed']);

        $data = [
            'order' => $order,
            'status_label' => $isPaid ? 'PAID' : 'UNPAID',
            'status_color' => $isPaid ? '#10b981' : '#ef4444',
        ];

        $pdf = Pdf::loadView('invoices.template', $data);

        return $pdf->download('Invoice-' . $order->invoice_no . '.pdf');
    }
}
