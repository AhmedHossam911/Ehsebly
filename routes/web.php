<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Legal Pages
Route::get('/privacy', function () {
    return view('pages.privacy'); })->name('privacy');
Route::get('/terms', function () {
    return view('pages.terms'); })->name('terms');
Route::get('/contact', function () {
    return view('pages.contact'); })->name('contact');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

    // Public Profile
    Route::get('/user/{user:uid}', [\App\Http\Controllers\PublicProfileController::class, 'show'])->name('profile.show');

    // Friends & Friend Requests
    Route::get('/friends', [\App\Http\Controllers\FriendController::class , 'index'])->name('friends.index');
    Route::delete('/friends/{friend}', [\App\Http\Controllers\FriendController::class , 'destroy'])->name('friends.destroy');

    Route::get('/friend-requests', [\App\Http\Controllers\FriendRequestController::class , 'index'])->name('friend-requests.index');
    Route::post('/friend-requests', [\App\Http\Controllers\FriendRequestController::class , 'store'])->name('friend-requests.store');
    Route::post('/friend-requests/{friendRequest}/accept', [\App\Http\Controllers\FriendRequestController::class , 'accept'])->name('friend-requests.accept');
    Route::post('/friend-requests/{friendRequest}/reject', [\App\Http\Controllers\FriendRequestController::class , 'reject'])->name('friend-requests.reject');

    // Events & Expenses
    Route::resource('events', \App\Http\Controllers\EventController::class);
    Route::post('/events/{event}/expenses', [\App\Http\Controllers\ExpenseController::class , 'store'])->name('expenses.store');
    Route::get('/events/{event}/expenses/{expense}/edit', [\App\Http\Controllers\ExpenseController::class , 'edit'])->name('expenses.edit');
    Route::put('/events/{event}/expenses/{expense}', [\App\Http\Controllers\ExpenseController::class , 'update'])->name('expenses.update');
    Route::delete('/events/{event}/expenses/{expense}', [\App\Http\Controllers\ExpenseController::class , 'destroy'])->name('expenses.destroy');

    // Debts
    Route::get('/debts', [\App\Http\Controllers\DebtController::class, 'index'])->name('debts.index');

    // Receipt Scanner
    Route::post('/receipts/scan', [\App\Http\Controllers\ReceiptScannerController::class , 'scan'])->name('receipts.scan');

    // Wallet
    Route::get('/wallet', [\App\Http\Controllers\WalletController::class , 'index'])->name('wallet.index');
    Route::post('/wallet/transactions', [\App\Http\Controllers\WalletController::class , 'store'])->name('wallet.transactions.store');

    // Settlements & InstaPay
    Route::post('/settlements/{settlement}/pay', [\App\Http\Controllers\SettlementController::class , 'pay'])->name('settlements.pay');
    Route::post('/settlements/{settlement}/confirm', [\App\Http\Controllers\SettlementController::class , 'confirm'])->name('settlements.confirm');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class , 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class , 'markAsRead'])->name('notifications.read');
});

// Guest Access Routes (No Auth Required)
Route::get('/e/{token}', [\App\Http\Controllers\GuestController::class , 'showJoinForm'])->name('guest.join.form');
Route::post('/e/{token}/join', [\App\Http\Controllers\GuestController::class , 'join'])->name('guest.join');
Route::get('/e/{token}/dashboard', [\App\Http\Controllers\GuestController::class , 'dashboard'])->name('guest.dashboard');

Route::post('/e/{token}/expenses', function (Illuminate\Http\Request $request, $token) {
    // Look up the event and route to ExpenseController
    $event = \App\Models\Event::where('guest_token', $token)->firstOrFail();
    return app(\App\Http\Controllers\ExpenseController::class)->store($request, $event);
})->name('guest.expenses.store');

require __DIR__ . '/auth.php';
