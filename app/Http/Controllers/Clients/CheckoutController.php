<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::with(['items.product', 'items.variant.attributes.attribute'])
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        // Kiểm tra nếu giỏ hàng không có sản phẩm
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.');
        }

        return view('pages.client.checkout', [
            'title' => 'Thanh toán',
            'cart' => $cart,
        ]);
    }
    public function getProvinces()
    {
        $response = Http::get('https://esgoo.net/api-tinhthanh/1/0.htm');
        return response()->json($response->json());
    }

    public function getDistricts(Request $request)
    {
        $provinceId = $request->query('province_id');
        $response = Http::get("https://esgoo.net/api-tinhthanh/2/{$provinceId}.htm");
        return response()->json($response->json());
    }

    public function getWards(Request $request)
    {
        $districtId = $request->query('district_id');
        $response = Http::get("https://esgoo.net/api-tinhthanh/3/{$districtId}.htm");
        return response()->json($response->json());
    }
}
