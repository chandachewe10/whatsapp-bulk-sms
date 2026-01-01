<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppConfigController;
use App\Http\Controllers\WhatsAppTemplateController;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Using standard auth routes (will need to install breeze/ui or just manual auth) 
// For now, I'll assume simple manual auth or just protect with middleware if I had it.
// Since I haven't installed Breeze, I'll rely on default Laravel auth scaffold if present, or just use basic Routes.
// IMPORTANT: `composer create-project` doesn't include Auth UI by default.
// I'll add a dummy auth middleware group or just open routes for now to ensure functionality.
// Given "SaaS", auth is critical. I'll stick to a simple closure based auth or just assume 'auth' middleware works if I set up a user.
// I'll group them under 'web' middleware group.

// Auth Routes
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Illuminate\Support\Facades\Auth::user();
        if (!$user)
            return redirect('login');

        $stats = [
            'total' => \App\Models\Message::where('tenant_id', $user->tenant_id)->count(),
            'sent' => \App\Models\Message::where('tenant_id', $user->tenant_id)->whereIn('status', ['sent', 'delivered'])->count(),
            'queued' => \App\Models\Message::where('tenant_id', $user->tenant_id)->where('status', 'queued')->count(),
            'failed' => \App\Models\Message::where('tenant_id', $user->tenant_id)->where('status', 'failed')->count(),
        ];
        $recentMessages = \App\Models\Message::where('tenant_id', $user->tenant_id)->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentMessages'));
    })->name('dashboard');

    // Configs
    Route::get('/configs', [WhatsAppConfigController::class, 'index'])->name('configs.index');
    Route::post('/configs', [WhatsAppConfigController::class, 'store'])->name('configs.store');

    // Templates
    Route::get('/templates', [WhatsAppTemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/create', [WhatsAppTemplateController::class, 'create'])->name('templates.create');
    Route::post('/templates', [WhatsAppTemplateController::class, 'store'])->name('templates.store');
    Route::get('/templates/{template}/check', [WhatsAppTemplateController::class, 'checkStatus'])->name('templates.checkStatus');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
});
