@extends ('layouts.client.main')
@section('content')
    @include('components.client.alert')
    <div class="container mt-4">
        <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
            @csrf
            <div class="row">
                <h3 class="text-center mb-3">THANH TOÁN ĐƠN HÀNG</h3>
                <div class="col-md-7">
                    <div class="card">
                        <div class="row">
                            <div class="mb-3 col-md-6 col-12">
                                <label for="fullName" class="form-label">Họ và tên *</label>
                                <input type="text" class="form-control" id="fullName" name="fullName"
                                    value="{{ auth()->user()->name }}" required>
                                <div class="invalid-feedback" style="display: none;"></div>
                            </div>
                            <div class="mb-3 col-md-6 col-12">
                                <label for="phone" class="form-label">Số điện thoại *</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ auth()->user()->phone }}" required>
                                <div class="invalid-feedback" style="display: none;"></div>
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label for="city" class="form-label">Tỉnh/Thành Phố *</label>
                                <select class="form-control" id="city" name="city" required></select>
                                <div class="invalid-feedback" style="display: none;"></div>
                            </div>
                            <div class="mb-3 col-md-6 col-12">
                                <label for="district" class="form-label">Quận/Huyện *</label>
                                <select class="form-control" id="district" name="district" required></select>
                                <div class="invalid-feedback" style="display: none;"></div>
                            </div>

                            <div class="mb-3 col-md-6 col-12">
                                <label for="ward" class="form-label">Phường/Xã *</label>
                                <select class="form-control" id="ward" name="ward" required></select>
                                <div class="invalid-feedback" style="display: none;"></div>
                            </div>
                            <div class="mb-3 col-md-6 col-12">
                                <label for="address" class="form-label">Địa chỉ *</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="Chợ trung hưng" required>
                                <div class="invalid-feedback" style="display: none;"></div>
                            </div>


                            <div class="mb-3 col-12">
                                <label for="note" class="form-label">Ghi chú đơn hàng (tùy chọn)</label>
                                <textarea class="form-control" id="note" name="note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header bg-danger">
                            <h5 class="mb-0 py-3">Đơn Hàng Của Bạn</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 text-start p-0">
                                    <strong>Sản phẩm</strong>
                                </div>
                                <div class="col-2 p-0 text-center">
                                    <strong>Số lượng</strong>
                                </div>
                                <div class="col-4 text-end">
                                    <strong>Tạm tính</strong>
                                </div>
                            </div>
                            <hr class="my-1">
                            @foreach ($cart->items as $item)
                                <div class="row">
                                    <div class="d-flex justify-content-between align-items-center pt-2">
                                        <div class="col-md-6 ">
                                            <div class="row">
                                                <div class="col-3 p-0">
                                                    <img src="{{ asset('images/' . $item->product->image_url) }}"
                                                        alt="{{ $item->product->name }}" class="img-fluid rounded">
                                                </div>
                                                <div class="col-9">
                                                    <span class="d-block">{{ $item->product->name }}</span>
                                                    @if ($item->variant)
                                                        @foreach ($item->variant->attributes as $attribute)
                                                            <span
                                                                class="d-block text-muted">{{ $attribute->attribute->attribute_name }}:
                                                                {{ $attribute->attribute_value }}</span>
                                                        @endforeach
                                                    @endif
                                                    <span>{{ number_format($item->price, 0, ',', '.') }}₫</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <span>{{ $item->quantity }}</span>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <hr class="my-1">
                            <div class="d-flex justify-content-between pt-2">
                                <span>Tạm tính</span>
                                {{ number_format($cart->items->sum(function ($item) {return $item->price * $item->quantity;}),0,',','.') }}₫
                            </div>
                            <div class="d-flex justify-content-between pt-2 d-none" id="voucherSection">
                                <span>Voucher</span>
                                <span id="voucherDiscount">-0₫</span>
                            </div>
                            <div class="d-flex justify-content-between pt-2">
                                <strong>Tổng</strong>
                                <strong
                                    id="totalAmount">{{ number_format($cart->items->sum(function ($item) {return $item->price * $item->quantity;}),0,',','.') }}₫</strong>
                            </div>

                            <div class="mb-3">
                                <label class="form-label ">Hình thức thanh toán</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="order_type" value="Cod"
                                        checked>
                                    <label class="form-check-label" for="shipCode">Thanh toán khi nhận hàng</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="order_type" value="VNPay">
                                    <label class="form-check-label" for="vnpay">Thanh toán qua VNPAY</label>
                                </div>
                            </div>

                            <!-- Voucher Input -->
                            <div class="mb-3">
                                <label for="voucher" class="form-label">Mã Voucher</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="voucher" name="voucher"
                                        placeholder="Nhập mã voucher">
                                    <button class="btn btn-primary" type="button" id="applyVoucherButton">Áp
                                        dụng</button>
                                </div>
                                <span id="voucherMessage" class="text-danger d-none">Mã voucher không hợp lệ</span>
                            </div>
                            <input type="hidden" name="total_amount"
                                value="{{ $cart->items->sum(function ($item) {return $item->price * $item->quantity;}) }}"
                                id="totalAmountInput">
                            <input type="hidden" name="discount_amount" id="discountAmountInput" value="0">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <button id="placeOrderButton" type="button" class="btn btn-danger">Đặt hàng</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/provinces')
                .then(response => response.json())
                .then(data => {
                    let citySelect = document.getElementById('city');
                    data.data.forEach(province => {
                        let option = document.createElement('option');
                        option.value = province.id;
                        option.text = province.full_name;
                        citySelect.appendChild(option);
                    });
                });
            document.getElementById('city').addEventListener('change', function() {
                let cityId = this.value;
                fetch(`/api/districts?province_id=${cityId}`)
                    .then(response => response.json())
                    .then(data => {
                        let districtSelect = document.getElementById('district');
                        districtSelect.innerHTML = '';
                        data.data.forEach(district => {
                            let option = document.createElement('option');
                            option.value = district.id;
                            option.text = district.full_name;
                            districtSelect.appendChild(option);
                        });
                    });
            });
            document.getElementById('district').addEventListener('change', function() {
                let districtId = this.value;
                fetch(`/api/wards?district_id=${districtId}`)
                    .then(response => response.json())
                    .then(data => {
                        let wardSelect = document.getElementById('ward');
                        wardSelect.innerHTML = '';
                        data.data.forEach(ward => {
                            let option = document.createElement('option');
                            option.value = ward.id;
                            option.text = ward.full_name;
                            wardSelect.appendChild(option);
                        });
                    });
            });
            document.getElementById('applyVoucherButton').addEventListener('click', function() {
                let voucherCode = document.getElementById('voucher').value;
                let totalAmount = parseInt(document.getElementById('totalAmountInput').value);

                if (voucherCode.trim() === '') {
                    document.getElementById('voucherMessage').innerText = 'Vui lòng nhập mã voucher';
                    document.getElementById('voucherMessage').classList.remove('d-none');
                    return;
                }

                fetch(`/apply-voucher?voucher=${voucherCode}&total_amount=${totalAmount}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let discount = data.discount;
                            let newTotalAmount = totalAmount - discount;
                            document.getElementById('totalAmount').innerText = newTotalAmount
                                .toLocaleString('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND'
                                });
                            document.getElementById('totalAmountInput').value = newTotalAmount;
                            document.getElementById('voucherDiscount').innerText =
                                `-${numberFormat(discount)}`;
                            document.getElementById('voucherSection').classList.remove('d-none');
                            document.getElementById('voucherMessage').classList.add('d-none');
                            document.getElementById('discountAmountInput').value = discount;
                        } else {
                            document.getElementById('voucherSection').classList.add('d-none');
                            document.getElementById('voucherMessage').innerText = data.message;
                            document.getElementById('voucherMessage').classList.remove('d-none');
                            document.getElementById('discountAmountInput').value = 0;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('voucherSection').classList.add('d-none');
                        document.getElementById('voucherMessage').innerText = 'Mã voucher không hợp lệ';
                        document.getElementById('voucherMessage').classList.remove('d-none');
                        document.getElementById('discountAmountInput').value = 0;
                    });
            });

            function numberFormat(number) {
                return new Intl.NumberFormat('vi-VN', {
                    minimumFractionDigits: 0
                }).format(number) + '₫';
            }

            document.getElementById('placeOrderButton').addEventListener('click', function(event) {
                let isValid = true;
                const requiredFields = ['fullName', 'phone', 'city', 'district', 'ward', 'address'];

                requiredFields.forEach(function(fieldId) {
                    const field = document.getElementById(fieldId);
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        field.nextElementSibling.innerText = 'Không được để trống';
                        field.nextElementSibling.style.display = 'block';
                    } else {
                        field.classList.remove('is-invalid');
                        field.nextElementSibling.innerText = '';
                        field.nextElementSibling.style.display = 'none';
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                } else {
                    let selectedPaymentMethod = document.querySelector('input[name="order_type"]:checked')
                        .value;
                    let checkoutForm = document.getElementById('checkoutForm');
                    if (selectedPaymentMethod === 'VNPay') {
                        checkoutForm.action = "{{ route('vnpay_payment') }}";
                    }
                    if (selectedPaymentMethod === 'Cod') {
                        checkoutForm.action = "{{ route('cod_payment') }}";
                    }
                    checkoutForm.submit();
                }
            });
        });
    </script>
@endsection
