<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AuthAdminController;
use App\Http\Controllers\Clients\AuthController as AuthClientController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Clients\HomeController as HomeClientController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PostCategoriesController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ProductCategoriesController;
use App\Http\Controllers\Admin\AttributesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Clients\VoucherController as ClientsVoucherController;
use App\Http\Controllers\Clients\AboutController;
use App\Http\Controllers\Clients\BlogsController;
use App\Http\Controllers\Clients\CartController;
use App\Http\Controllers\Clients\CheckoutController;
use App\Http\Controllers\Clients\ContactController;
use App\Http\Controllers\Clients\OrdersContrller;
use App\Http\Controllers\Clients\PaymentController;
use App\Http\Controllers\Clients\ProductsController as ClientsProductsController;
use App\Http\Controllers\Clients\AccountController as ClientsAccountController;
use App\Http\Controllers\Clients\FavoriteController;
use App\Http\Controllers\Clients\GoogleController;

// test 
// Route::middleware(['auth', 'authUser:1'])->group(function () {
//     Route::get('/admintest', [HomeController::class, 'index'])->name('manager.dashboard');
// });
// test 


Route::get('/dang-nhap', [AuthClientController::class, 'showLoginForm'])->name('login');
Route::post('/xu-ly-dang-nhap', [AuthClientController::class, 'login'])->name('login.store');
Route::get('/dang-ky', [AuthClientController::class, 'showRegistrationForm'])->name('register');
Route::post('/xu-ly-dang-ky', [AuthClientController::class, 'register'])->name('register.store');
Route::get('/dang-xuat', [AuthClientController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/authorized/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/authorized/google/callback', [GoogleController::class, 'handleGoogleCallback']);
// Client 
Route::get('/', [HomeClientController::class, 'index'])->name('home.index');

Route::get('/san-pham', [ClientsProductsController::class, 'index'])->name('productsClient.index');
Route::get('/san-pham/{slug?}', [ClientsProductsController::class, 'productByCategory'])->name('productsClient.productByCategory');
Route::get('/san-pham/{id}/chi-tiet', [ClientsProductsController::class, 'show'])->name('productsClient.show');
Route::get('/tin-tuc', [BlogsController::class, 'index'])->name('blog.index');
Route::get('/tin-tuc/{slug}', [BlogsController::class, 'showByCategory'])->name('blog.by.category');
Route::get('/tin-tuc/chi-tiet/{id}', [BlogsController::class, 'show'])->name('blog.show');
Route::middleware(['auth',])->group(function () {
    Route::get('/favorites/add/{productId}', [FavoriteController::class, 'add'])->name('favorites.add');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::delete('/favorites/{id}', [FavoriteController::class, 'remove'])->name('favorites.remove');
    // giỏ hàng 
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    // Thanh toán 
    Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/xu-ly-thanh-toan', [PaymentController::class, 'processOrder'])->name('checkout.process');
    // Route::get('/api/provinces', [CheckoutController::class, 'getProvinces']);
    // Route::get('/api/districts', [CheckoutController::class, 'getDistricts']);
    // Route::get('/api/wards', [CheckoutController::class, 'getWards']);
    //Cổng thanh toán 
    Route::post('/vnpay_payment', [PaymentController::class, 'vnpay_payment'])->name('vnpay_payment');
    Route::post('/cod_payment', [PaymentController::class, 'cod_payment'])->name('cod_payment');
    Route::get('/don-hang/{id}',  [OrdersContrller::class, 'show'])->name('orderReceived');
    // voucher 
    Route::get('/apply-voucher', [ClientsVoucherController::class, 'applyVoucher'])->name('apply.voucher');
    // Đơn hàng
    Route::get('/don-hang-cua-toi', [OrdersContrller::class, 'myOrders'])->name('orders.my');
    //Tài khoản
    Route::get('/tai-khoan', [ClientsAccountController::class, 'index'])->name('account.index');
    Route::post('/tai-khoan/update/{id}', [ClientsAccountController::class, 'update'])->name('account.update');
    Route::get('/tai-khoan/doi-mat-khau', [ClientsAccountController::class, 'showChangePasswordForm'])->name('account.change_password');
    Route::post('/tai-khoan/doi-mat-khau', [ClientsAccountController::class, 'changePassword'])->name('account.change_password.update');
});

Route::get('/lien-he', [ContactController::class, 'index'])->name('contact.index');
Route::get('/gioi-thieu', [AboutController::class, 'index'])->name('about.index');

// Admin 
Route::get('/admin/dang-nhap', [AuthAdminController::class, 'showLoginForm'])->name('loginAdmin');
Route::post('/admin/xu-ly-dang-nhap', [AuthAdminController::class, 'login'])->name('loginAdmin.store');
Route::get('/admin/dang-ky', [AuthAdminController::class, 'showRegistrationForm'])->name('registerAdmin');
Route::post('/admin/xu-ly-dang-ky', [AuthAdminController::class, 'register'])->name('registerAdmin.store');
Route::get('/admin/dang-xuat', [AuthAdminController::class, 'logout'])->name('logoutAdmin')->middleware('auth');
// authUser:1 => admin 
Route::middleware(['auth', 'authUser:1'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('admin.home.index');
        Route::get('/test', [HomeController::class, 'test'])->name('admin.home.test');
        Route::prefix('banners')->group(function () {
            Route::get('/danh-sach', [BannersController::class, 'index'])->name('banner.index');
            Route::get('/them', [BannersController::class, 'create'])->name('banner.create');
            Route::post('/them-xu-ly', [BannersController::class, 'store'])->name('banner.store');
            Route::get('/sua/{id}', [BannersController::class, 'edit'])->name('banner.edit');
            Route::put('/banner/{id}', [BannersController::class, 'update'])->name('banner.update');
            Route::put('/toggleBannerStatus/{id}', [BannersController::class, 'toggleBannerStatus'])->name('banner.updateStatus');
            Route::delete('/xoa/{id}', [BannersController::class, 'destroy'])->name('banner.destroy');
        });
        Route::prefix('menu')->group(function () {
            Route::get('/danh-sach', [MenuController::class, 'index'])->name('menu.index');
            Route::get('/them', [MenuController::class, 'create'])->name('menu.create');
            Route::post('/them-xu-ly', [MenuController::class, 'store'])->name('menu.store');
            Route::get('/sua/{id}', [MenuController::class, 'edit'])->name('menu.edit');
            Route::put('/sua-xu-ly/{id}', [MenuController::class, 'update'])->name('menu.update');
            Route::delete('/xoa/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');
        });

        Route::prefix('tai-khoan')->group(function () {
            Route::get('/danh-sach', [UsersController::class, 'index'])->name('user.index');
            Route::get('/them', [UsersController::class, 'create'])->name('user.create');
            Route::post('/them-xu-ly', [UsersController::class, 'store'])->name('user.store');
            Route::get('/chi-tiet/{id}', [UsersController::class, 'show'])->name('user.show');
            Route::get('/sua/{id}', [UsersController::class, 'edit'])->name('user.edit');
            Route::put('/sua-xu-ly/{id}', [UsersController::class, 'update'])->name('user.update');
            Route::delete('/xoa/{id}', [UsersController::class, 'destroy'])->name('user.destroy');
            Route::post('/bulk-delete', [UsersController::class, 'bulkDelete'])->name('user.bulkDelete');
        });
        Route::prefix('loai-bai-viet')->group(function () {
            Route::get('/danh-sach', [PostCategoriesController::class, 'index'])->name('postCategory.index');
            Route::get('/them', [PostCategoriesController::class, 'create'])->name('postCategory.create');
            Route::post('/them-xu-ly', [PostCategoriesController::class, 'store'])->name('postCategory.store');
            Route::get('/sua/{id}', [PostCategoriesController::class, 'edit'])->name('postCategory.edit');
            Route::put('/sua-xu-ly/{id}', [PostCategoriesController::class, 'update'])->name('postCategory.update');
            Route::delete('/xoa/{id}', [PostCategoriesController::class, 'destroy'])->name('postCategory.destroy');
        });
        Route::prefix('bai-viet')->group(function () {
            Route::get('/danh-sach', [PostsController::class, 'index'])->name('post.index');
            Route::get('/them', [PostsController::class, 'create'])->name('post.create');
            Route::post('/them-xu-ly', [PostsController::class, 'store'])->name('post.store');
            Route::get('/chi-tiet/{id}', [PostsController::class, 'show'])->name('post.show');
            Route::get('/sua/{id}', [PostsController::class, 'edit'])->name('post.edit');
            Route::put('/sua-xu-ly/{id}', [PostsController::class, 'update'])->name('post.update');
            Route::delete('/xoa/{id}', [PostsController::class, 'destroy'])->name('post.destroy');
            Route::post('/bulk-delete', [PostsController::class, 'bulkDelete'])->name('posts.bulkDelete');
        });
        Route::prefix('danh-muc')->group(function () {
            Route::get('/danh-sach', [ProductCategoriesController::class, 'index'])->name('productCategories.index');
            Route::get('/them', [ProductCategoriesController::class, 'create'])->name('productCategories.create');
            Route::post('/them-xu-ly', [ProductCategoriesController::class, 'store'])->name('productCategories.store');
            Route::get('/sua/{id}', [ProductCategoriesController::class, 'edit'])->name('productCategories.edit');
            Route::put('/sua-xu-ly/{id}', [ProductCategoriesController::class, 'update'])->name('productCategories.update');
            Route::delete('/xoa/{id}', [ProductCategoriesController::class, 'destroy'])->name('productCategories.destroy');
            Route::put('/sua-trang-thai/{id}', [ProductCategoriesController::class, 'updateStatus'])->name('productCategories.updateStatus');
        });
        Route::prefix('san-pham')->group(function () {
            Route::get('/', [ProductsController::class, 'index'])->name('products.index');
            Route::get('/them', [ProductsController::class, 'create'])->name('product.create');
            Route::get('/test', [ProductsController::class, 'test'])->name('product.test');
            Route::post('/them-xu-ly', [ProductsController::class, 'store'])->name('products.store');
            Route::get('/chi-tiet/{id}', [ProductsController::class, 'show'])->name('product.show');
            Route::get('/sua/{id}', [ProductsController::class, 'edit'])->name('product.edit');
            Route::put('/sua-xu-ly/{id}', [ProductsController::class, 'update'])->name('products.update');
            Route::delete('/xoa/{id}', [ProductsController::class, 'destroy'])->name('product.destroy');
            Route::post('/bulk-delete', [ProductsController::class, 'bulkDelete'])->name('products.bulkDelete');
        });
        Route::prefix('thuoc-tinh')->group(function () {
            Route::get('/danh-sach', [AttributesController::class, 'index'])->name('attributes.index');
            Route::get('/them', [AttributesController::class, 'create'])->name('attributes.create');
            Route::post('/them-xu-ly', [AttributesController::class, 'store'])->name('attributes.store');
            Route::get('/chi-tiet/{id}', [AttributesController::class, 'show'])->name('attributes.show');
            Route::get('/sua/{id}', [AttributesController::class, 'edit'])->name('attributes.edit');
            Route::put('/sua-xu-ly/{id}', [AttributesController::class, 'update'])->name('attributes.update');
            Route::delete('/xoa/{id}', [AttributesController::class, 'destroy'])->name('attributes.destroy');
            Route::put('/sua-trang-thai/{id}', [ProductCategoriesController::class, 'updateStatus'])->name('attributes.updateStatus');
        });
        Route::prefix('hoa-don')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index'])->name('admin.orders.index');
            Route::get('/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
            Route::put('/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        });
        Route::prefix('voucher')->group(function () {
            Route::get('/', [VoucherController::class, 'index'])->name('vouchers.index');
            Route::get('/create', [VoucherController::class, 'create'])->name('vouchers.create');
            Route::post('vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
            Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
            Route::put('/{voucher}', [VoucherController::class, 'update'])->name('vouchers.update');
            Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
        });
        Route::prefix('giam-gia')->group(function () {
            Route::get('/', [DiscountController::class, 'index'])->name('discounts.index');
            Route::get('/create', [DiscountController::class, 'create'])->name('discounts.create');
            Route::post('/store', [DiscountController::class, 'store'])->name('discounts.store');
            Route::get('/{discount}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');
            Route::put('/{discount}', [DiscountController::class, 'update'])->name('discounts.update');
            Route::post('/bulk-delete', [DiscountController::class, 'bulkDelete'])->name('discounts.bulkDelete');
        });
    });
});
