<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DateConvertController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\PasswordCodeController;
use App\Http\Controllers\AdminPasskeyController;
use App\Http\Controllers\SettingsController;



use App\Http\Controllers\UserController;


Route::get('/about',  [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// Contact form submit
Route::post('/contact', [ContactController::class, 'send'])->middleware(['spam', 'throttle:5,10'])->name('contact.send');


// Show the form to request a password reset link
// Customer "forgot password": emailed 6-digit code
Route::get('forgot-password', [PasswordCodeController::class, 'request'])->name('password.request');
Route::post('forgot-password', [PasswordCodeController::class, 'send'])->middleware('throttle:5,10')->name('password.email');
Route::get('reset-password', [PasswordCodeController::class, 'form'])->name('password.reset.code');
Route::post('reset-password', [PasswordCodeController::class, 'update'])->middleware('throttle:10,10')->name('password.update');
// Old emailed links now lead to the code flow
Route::get('reset-password/{token}', fn () => redirect()->route('password.request'))->name('password.reset');

// Authentication routes
Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/callback', [GoogleAuthController::class, 'callbackGoogle']);
Route::get('auth/facebook', [GoogleAuthController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [GoogleAuthController::class, 'handleFacebookCallback']);
Route::get("/login", [GoogleAuthController::class, 'login'])->name('login');
Route::post("auth", [GoogleAuthController::class, 'auth']);



// Frontend Section

Route::get('customer', [GoogleAuthController::class, 'signup'])->name('customer.create');
Route::post('customer', [GoogleAuthController::class, 'store'])->middleware(['spam', 'throttle:5,10'])->name('customer.store');
Route::get("/admin/orderlist", [AdminController::class, 'orderlist']);
Route::get("/products/{name}/{id}", [FrontendController::class, 'show']);
Route::get("show/products", [FrontendController::class, 'index'])->name('products.index');
Route::get("/", [FrontendController::class, 'index'])->name('home');


Route::get("/shopping", [FrontendController::class, 'shop'])->name('shop');

Route::middleware(['auth'])->group(function () {
    Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('cart/updateQuantity/{id}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('cart/show', [CartController::class, 'show'])->name('cart.show');
    Route::get('order/{name}/{id}', [CartController::class, 'index'])->name('order.create');
    Route::post('/place-order', [CartController::class, 'store'])->name('place.order');
    Route::get('logout', [FrontendController::class, 'logout'])->name('logout');
});






#Backend Section
Route::get("/admin", [AdminController::class, 'index']);
Route::post("/admin", [AdminController::class, 'login'])->middleware('throttle:10,1')->name('admin');

// Admin "forgot password" (6-digit code) and passkey login
Route::get('/admin/forgot-password', [PasswordCodeController::class, 'request'])->name('admin.password.request');
Route::post('/admin/forgot-password', [PasswordCodeController::class, 'send'])->middleware('throttle:5,10')->name('admin.password.email');
Route::get('/admin/reset-password', [PasswordCodeController::class, 'form'])->name('admin.password.reset');
Route::post('/admin/reset-password', [PasswordCodeController::class, 'update'])->middleware('throttle:10,10')->name('admin.password.update');
Route::post('/admin/passkey/options', [AdminPasskeyController::class, 'loginOptions'])->middleware('throttle:20,1')->name('admin.passkey.options');
Route::post('/admin/passkey/login', [AdminPasskeyController::class, 'login'])->middleware('throttle:10,1')->name('admin.passkey.login');
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('brands', BrandController::class);
    Route::get('/admin/products', [ProductsController::class, 'index'])->name('product.index');
    Route::get('/admin/products/create', [ProductsController::class, 'create'])->name('product.create');
    Route::post('/admin/products', [ProductsController::class, 'store'])->name('product.store');


    Route::get('/admin/products/{product}/edit', [ProductsController::class, 'edit'])->name('product.edit');
    Route::put('/admin/products/update/{product}', [ProductsController::class, 'update'])->name('product.update');
    Route::delete('/admin/products/{id}', [ProductsController::class, 'destroy'])->name('product.destroy');
    Route::delete('/admin/products/{product}/images/{image}', [ProductsController::class, 'destroyImage'])->name('product.image.destroy');


    // Blog: AI drafts are generated here by a logged-in admin, then reviewed and published
    // Settings: email (SMTP), WhatsApp, passkeys
    Route::get('/admin/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/admin/settings/mail', [SettingsController::class, 'saveMail'])->name('admin.settings.mail');
    Route::post('/admin/settings/mail/test', [SettingsController::class, 'testMail'])->middleware('throttle:10,10')->name('admin.settings.mail.test');
    Route::post('/admin/settings/whatsapp', [SettingsController::class, 'saveWhatsApp'])->name('admin.settings.whatsapp');
    Route::post('/admin/settings/whatsapp/test', [SettingsController::class, 'testWhatsApp'])->middleware('throttle:10,10')->name('admin.settings.whatsapp.test');
    Route::post('/admin/passkeys/options', [AdminPasskeyController::class, 'registerOptions'])->name('admin.passkeys.options');
    Route::post('/admin/passkeys', [AdminPasskeyController::class, 'register'])->name('admin.passkeys.store');
    Route::delete('/admin/passkeys/{passkey}', [AdminPasskeyController::class, 'destroy'])->name('admin.passkeys.destroy');

    Route::prefix('admin/blog')->name('admin.blog.')->group(function () {
        Route::get('/', [BlogPostController::class, 'index'])->name('index');
        Route::post('/generate', [BlogPostController::class, 'generate'])->middleware('throttle:10,60')->name('generate');
        Route::get('/create', [BlogPostController::class, 'create'])->name('create');
        Route::post('/', [BlogPostController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [BlogPostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [BlogPostController::class, 'update'])->name('update');
        Route::delete('/{post}', [BlogPostController::class, 'destroy'])->name('destroy');
    });

    Route::get('/admin/banners', [BannerController::class, 'index'])->name('banner.index');
    Route::get('/admin/banners/create', [BannerController::class, 'create'])->name('banner.create');
    Route::post('/admin/banners', [BannerController::class, 'store'])->name('banner.store');
    Route::delete('/admin/banners/{banner}', [BannerController::class, 'destroy'])->name('banner.destroy');

    Route::resource('suppliers', SupplierController::class);


    Route::resource('brand', BrandController::class);
    Route::resource('categories', CategoryController::class);
    Route::post("/admin/logout", [AdminController::class, 'logout'])->name('admin.logout');
    Route::post('/generate-seo', [AdminController::class, 'generateSeo'])
        ->name('product.generateSeo');



    Route::prefix('admin/orders')->name('admin.orders.')->group(function () {
        Route::get('/admins/orders/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show'); // <-- NEW
        Route::post('/verify/{orderItem}', [OrderController::class, 'verify'])->name('verify');
        Route::post('/ship/{orderItem}', [OrderController::class, 'ship'])->name('ship');
        Route::post('/deliver/{orderItem}', [OrderController::class, 'deliver'])->name('deliver');
        Route::delete('/delete/{order}', [OrderController::class, 'destroy'])->name('delete');



        // NEW: Invoice (print view)
        Route::get('/{order}/invoice', [OrderController::class, 'invoiceHtml'])->name('invoice.html');

        // NEW: Invoice PDF (optional, requires barryvdh/laravel-dompdf)
        Route::get('/{order}/invoice-pdf', [OrderController::class, 'invoicePdf'])->name('invoice.pdf');
    });

    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/{product}',      [StockController::class, 'show'])->name('show');
        Route::post('/stock/{product}/refill', [StockController::class, 'refill'])->name('refill');
        Route::put('/stock/purchase/{purchase}', [StockController::class, 'updatePurchase'])
            ->name('purchase.update');
        Route::delete('/purchase/{purchase}', [StockController::class, 'deletePurchase'])->name('purchase.delete');



        Route::get('/suppliers/{supplier}/purchases', [StockController::class, 'bySupplier'])
            ->name('suppliers.purchases');

            
        Route::get('/summary', [StockController::class, 'summary'])->name('summary');
    });

    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/',        [BillingController::class, 'index'])->name('index');
        Route::get('/create',  [BillingController::class, 'create'])->name('create');
        Route::post('/',       [BillingController::class, 'store'])->name('store');

        // Profit & Loss button/route
        Route::get('/profit-loss', [BillingController::class, 'profitLoss'])->name('profit');
        Route::get('/{sale}/print', [BillingController::class, 'print'])->name('print');
Route::put('/billing/{sale}/inline', [BillingController::class, 'inlineUpdate'])
     ->name('inline.update');

        Route::delete('/{sale}',    [BillingController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('credits')->name('credits.')->group(function () {
    Route::get('/',            [CreditController::class, 'index'])->name('index');
    Route::get('/create',      [CreditController::class, 'create'])->name('create');
    Route::post('/',           [CreditController::class, 'store'])->name('store');
    Route::get('/{credit}',    [CreditController::class, 'show'])->name('show');
    Route::post('/{credit}/payment', [CreditController::class, 'addPayment'])->name('payment');
    Route::post('/{credit}/close',   [CreditController::class, 'close'])->name('close');
});

Route::get('/utils/bs-to-ad', [DateConvertController::class, 'bsToAd'])->name('utils.bs2ad');
Route::get('/utils/ad-to-bs', [DateConvertController::class, 'adToBs'])->name('utils.ad2bs'); // optional


    Route::get('/users', [UserController::class, 'users'])->name('users.index');
});
