<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\TicketController as UserTicketController;
use App\Http\Controllers\User\KnowledgeBaseController as UserKbController;
use App\Http\Controllers\Support\DashboardController as SupportDashboardController;
use App\Http\Controllers\Support\TicketController as SupportTicketController;
use App\Http\Controllers\Support\KnowledgeBaseController as SupportKbController;
use App\Http\Controllers\Support\AiAssistantController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware(['auth','last.active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User Routes
    Route::middleware('role:user')->group(function () {
        Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
        Route::post('/profile', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
        
        Route::get('/tickets', [UserTicketController::class, 'index'])->name('user.tickets.index');
        Route::get('/tickets/create', [UserTicketController::class, 'create'])->name('user.tickets.create');
        Route::post('/tickets', [UserTicketController::class, 'store'])->name('user.tickets.store');
        Route::get('/tickets/{ticket}', [UserTicketController::class, 'show'])->name('user.tickets.show');
        Route::post('/tickets/{ticket}/comment', [UserTicketController::class, 'comment'])->name('user.tickets.comment');

        Route::get('/kb', [UserKbController::class, 'index'])->name('user.kb.index');
        Route::get('/kb/{article}', [UserKbController::class, 'show'])->name('user.kb.show');
    });

    // IT Support Routes
    Route::prefix('support')->name('support.')->middleware('role:it_support')->group(function () {
        Route::get('/profile', [SupportDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [SupportDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::get('/dashboard', [SupportDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/tickets', [SupportTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets-export', [SupportTicketController::class, 'exportCsv'])->name('tickets.export');
        Route::get('/tickets/{ticket}', [SupportTicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/generate-ai', [SupportTicketController::class, 'generateAiRecommendation'])->name('tickets.generate-ai');
        Route::post('/tickets/{ticket}/status', [SupportTicketController::class, 'updateStatus'])->name('tickets.status');
        Route::post('/tickets/{ticket}/assign', [SupportTicketController::class, 'assign'])->name('tickets.assign');
        Route::post('/tickets/{ticket}/comment', [SupportTicketController::class, 'comment'])->name('tickets.comment');

        Route::resource('kb', SupportKbController::class);

        Route::get('/ai', [AiAssistantController::class, 'index'])->name('ai.index');
        Route::post('/ai/summarize', [AiAssistantController::class, 'summarizeLatest'])->name('ai.summarize');
    });
});
