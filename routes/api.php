<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/google-login', [\App\Http\Controllers\GoogleAuthController::class, 'login']);

Route::post('/signOut', [\App\Http\Controllers\AuthController::class, 'signOut'])->middleware("auth:sanctum");



Route::get('/', function () {
    return response()->json(['message' => 'Welcome to the API']);
});

// Routes d'admin

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('products', AdminProductController::class);
    // Autres routes admin...
});
// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Routes de profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.edit-password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/orders/{id}', [ProfileController::class, 'orderDetails'])->name('profile.order-details');
});

// Routes de vérification
Route::get('/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
Route::post('/verify', [AuthController::class, 'verifyCode'])->name('verification.verify');
Route::post('/verify/resend', [AuthController::class, 'resendVerificationCode'])->name('verification.resend');

// Routes pour les catégories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/shop', [ProductController::class, 'getShopProducts']);
    Route::get('/products/new', [ProductController::class, 'getNewProducts']);
    Route::get('/products/trending', [ProductController::class, 'getTrendingProducts']);
    Route::get('/products/promo', [ProductController::class, 'getPromoProducts']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/{id}/image', function ($id) {
    $product = Product::findOrFail($id);
    $color = request('color');
    
    return response()->json([
        'image_url' => $product->getImageForColor($color)
    ]);
});

    Route::get('/products/{id}/reviews', [ProductController::class, 'getReviews']);
// Routes pour les commentaires
Route::get('/products/{productId}/comments', [CommentController::class, 'index']);
Route::post('/products/{productId}/comments', [CommentController::class, 'store'])->middleware('auth:sanctum');
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->middleware('auth:sanctum');


// Routes pour le panier
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

// Routes pour le paiement
Route::get('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout')->middleware('auth');
Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('payment.process')->middleware('auth');
Route::get('/payment-success/{command}', [PaymentController::class, 'success'])->name('payment.success')->middleware('auth');

// Route pour la réinitialisation du mot de passe
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');


Route::get('/products/filter', [ProductController::class, 'filter']);
