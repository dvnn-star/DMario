<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Session Meja
        $tableSession = session('table');
        if (! $tableSession || ! isset($tableSession['id'])) {
            return redirect()->back()->withErrors(['table' => 'Session meja tidak valid. Silakan scan ulang QR Code.']);
        }

        // 2. Validasi Payload dari Frontend Vue
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,qris,transfer',
        ]);

        // 3. Eksekusi Simpan dengan DB Transaction (Atomis)
        try {
            $order = DB::transaction(function () use ($validated, $tableSession) {
                // Hitung total harga server-side (Jangan percaya hitungan harga dari frontend!)
                $totalPrice = 0;
                $orderDetailsData = [];

                foreach ($validated['items'] as $item) {
                    $menuItem = MenuItem::findOrFail($item['id']);
                    $itemTotal = $menuItem->price * $item['quantity'];
                    $totalPrice += $itemTotal;

                    $orderDetailsData[] = [
                        'menu_item_id' => $menuItem->id,
                        'quantity' => $item['quantity'],
                        'price' => $menuItem->price,
                    ];
                }

                // A. Buat Header Pesanan
                $order = order::create([
                    'table_id' => $tableSession['id'],
                    'status' => 'pending',
                    'payment_method' => $validated['payment_method'],
                    'total_price' => $totalPrice,
                ]);

                // B. Buat Detail Pesanan
                $order->orderDetails()->createMany($orderDetailsData);

                return $order;
            });

            session(['active_order_id' => $order->id]);

            return redirect()->route('order.status');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal memproses pesanan: '.$e->getMessage()]);
        }
    }
}
