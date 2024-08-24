<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class OrdersContrller extends Controller
{
    public function show($id)
    {
        $order = Order::with('items.product', 'items.variant.attributes.attribute')->findOrFail($id);
        // Lấy tên tỉnh/thành phố, quận/huyện, và phường/xã từ API
        $address = Http::get("https://esgoo.net/api-tinhthanh/5/{$order->ward}.htm")->json();
        // Kiểm tra xem có lỗi hay không và dữ liệu có tồn tại không
        if ($address['error'] === 0 && isset($address['data'])) {
            // Lấy giá trị full_name từ mảng data
            $address = $address['data']['full_name'] ?? 'N/A';
        } else {
            echo "Lỗi khi lấy dữ liệu hoặc không có dữ liệu.";
        }
        $title = 'Thanh toán hóa đơn';
        return view('pages.client.orderReceived', compact('order', 'title','address'));
    }
    public function myOrders()
    {
        $title = 'Đơn hàng của tôi';
        $orders = Order::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('pages.client.myOrders', compact('orders', 'title'));
    }
}
