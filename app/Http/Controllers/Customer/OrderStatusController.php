<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderStatusController extends Controller
{
    public function __invoke(Request $request)
    {
        $orderId = session('active_order_id');

        if (! $orderId) {
            // Ambil identifier (UUID) meja dari session yang tersimpan saat scan QR
            $tableIdentifier = session('table.identifier') ?? session('table_identifier');

            if ($tableIdentifier) {
                return redirect()->to("/menu/table/{$tableIdentifier}");
            }

            // Fallback jika sesi meja ikut hilang
            return abort(403, 'Sesi meja tidak ditemukan. Silakan scan ulang QR Code di meja Anda.');
        }

        $order = order::with(['table', 'orderDetails.menuItem'])
            ->findOrFail($orderId);

        return Inertia::render('MenuQr/OrderStatus', [
            'order' => $order,
        ]);
    }
}