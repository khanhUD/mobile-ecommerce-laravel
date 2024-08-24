<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Exception;

class PaymentController extends Controller
{
    public function processOrder(Request $request)
    {
        // Validate the input
        $request->validate([
            'fullName' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:10',
            'note' => 'nullable|string',
            'paymentMethod' => 'required|string',
        ]);

        $paymentMethod = $request->input('paymentMethod');
        if ($paymentMethod === 'VNPay') {
            // Lưu trữ thông tin đơn hàng vào session
            session([
                'order_info' => $request->all()
            ]);
            // Chuyển hướng tới trang thanh toán của VNPay
            return $this->processVnpayPayment($request);
        } else if ($paymentMethod === 'Cod') {
            // Tạo đơn hàng cho thanh toán COD
            return $this->createOrder($request, 'Cod');
        }

        return back()->with('error', 'Phương thức thanh toán không hợp lệ.');
    }

    private function processVnpayPayment($request)
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpayReturn'); // Thay đổi để phù hợp với route của bạn
        $vnp_TmnCode = "N5W6AA0O"; // Mã website tại VNPAY
        $vnp_HashSecret = "LMFQ08Y3JOATOR2QECTGA7DTOZC76RRS"; // Chuỗi bí mật

        $vnp_TxnRef = date("YmdHis"); // Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toán đơn hàng';
        $vnp_OrderType = 'Thanh toán vnpay';
        $vnp_Amount = $request->input('total_amount') * 100; // VNPay sử dụng đơn vị VND và nhân 100
        $vnp_Locale = 'VN';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = request()->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    private function createOrder($orderData, $paymentMethod, $transactionId = null, $status = 'pending')
    {
        // Lấy giỏ hàng của người dùng
        $cart = Cart::where('user_id', auth()->id())->where('status', 'active')->first();

        // Kiểm tra nếu giỏ hàng không tồn tại
        if (!$cart) {
            throw new Exception('Giỏ hàng không tồn tại hoặc đã hết hạn.');
        }

        // Tạo mã đơn hàng duy nhất
        $orderCode = $this->generateOrderCode();

        // Xử lý voucher nếu có
        if ($orderData['voucher']) {
            $this->handleVoucher($orderData['voucher']);
        }

        // Tạo đơn hàng
        $order = $this->createOrderRecord($orderData, $cart, $orderCode, $paymentMethod, $transactionId, $status);

        // Tạo các chi tiết đơn hàng và trừ kho
        $this->createOrderItemsAndDeductStock($cart, $order);

        // Xóa giỏ hàng và các mục trong giỏ hàng
        $this->clearCart($cart);
        return redirect()->route('orderReceived', ['id' => $order->id])->with('success', 'Đặt hàng thành công.');
    }

    private function generateOrderCode()
    {
        return date('YmdHis') . strtoupper(uniqid());
    }

    private function handleVoucher($voucherCode)
    {
        $voucher = Voucher::where('code', $voucherCode)->first();

        if (!$voucher) {
            throw new Exception('Mã voucher không hợp lệ.');
        }

        if ($voucher->used >= $voucher->quantity) {
            throw new Exception('Voucher này đã hết lượt sử dụng.');
        }

        // Cập nhật số lần sử dụng của voucher
        $voucher->increment('used');
    }

    private function createOrderRecord($orderData, $cart, $orderCode, $paymentMethod, $transactionId, $status)
    {
        return Order::create([
            'user_id' => auth()->id(),
            'order_code' => $orderCode,
            'full_name' => $orderData['fullName'],
            'phone' => $orderData['phone'],
            'city' => $orderData['city'],
            'district' => $orderData['district'],
            'ward' => $orderData['ward'],
            'address' => $orderData['address'],
            'note' => $orderData['note'],
            'total_amount' => $cart->items->sum(function ($item) {
                return $item->price * $item->quantity;
            }),
            'discount_amount' => $orderData['discount_amount'] ?? 0,
            'final_amount' => $cart->items->sum(function ($item) {
                return $item->price * $item->quantity;
            }) - ($orderData['discount_amount'] ?? 0),
            'voucher_code' => $orderData['voucher'],
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'status' => $status,
        ]);
    }

    private function createOrderItemsAndDeductStock($cart, $order)
    {
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total_price' => $item->price * $item->quantity,
            ]);

            // Trừ số lượng sản phẩm trong kho
            if ($item->variant_id) {
                $variant = ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->decrement('stock', $item->quantity);
                }
            } else {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('store_quantity', $item->quantity);
                }
            }
        }
    }

    private function clearCart($cart)
    {
        CartItem::where('cart_id', $cart->id)->delete();
        $cart->delete();
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "LMFQ08Y3JOATOR2QECTGA7DTOZC76RRS"; // Chuỗi bí mật
        $inputData = $request->all();

        // Lấy giá trị vnp_SecureHash và loại bỏ khỏi mảng dữ liệu đầu vào
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        // Sắp xếp các tham số theo thứ tự bảng chữ cái
        ksort($inputData);
        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $hashData = rtrim($hashData, '&');

        // Tạo chữ ký bảo mật từ dữ liệu đã sắp xếp
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        // So sánh chữ ký bảo mật để xác thực
        if ($secureHash == $vnp_SecureHash) {
            if ($inputData['vnp_ResponseCode'] == '00') {
                // Lấy thông tin đơn hàng từ session
                $orderData = session('order_info');
                // Tạo đơn hàng
                try {
                    $order = $this->createOrder($orderData, 'VNPay', $inputData['vnp_TransactionNo'], 'completed');
                    return $order;
                } catch (Exception $e) {
                    return redirect()->route('checkout.index')->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
                }
            } else {
                // Thanh toán không thành công
                return redirect()->route('checkout.index')->with('error', 'Thanh toán không thành công. Vui lòng thử lại.');
            }
        } else {
            return redirect()->route('checkout.index')->with('error', 'Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại.');
        }
    }
}
