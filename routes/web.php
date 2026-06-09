<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SearchController;

use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\TicketController as UserTicketController;
use App\Http\Controllers\User\KnowledgeBaseController as UserKbController;

use App\Http\Controllers\Support\DashboardController as SupportDashboardController;
use App\Http\Controllers\Support\TicketController as SupportTicketController;
use App\Http\Controllers\Support\KnowledgeBaseController as SupportKbController;
use App\Http\Controllers\Support\AiAssistantController;

// Halaman utama langsung diarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});


// ==============================
// Guest Routes
// ==============================
// Route di bawah ini hanya bisa diakses oleh tamu (belum login)
Route::middleware('guest')->group(function () {

    // Menampilkan dan memproses halaman login
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    // Menampilkan dan memproses halaman registrasi akun baru
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    // Menampilkan dan memproses form reset password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'updatePassword'])
        ->name('password.update.direct');
});


// ==============================
// Authenticated Routes
// ==============================
// Route di bawah ini hanya bisa diakses oleh user yang sudah login
Route::middleware(['auth', 'last.active'])->group(function () {

    // Route untuk logout dari sistem
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // ==============================
    // GLOBAL SEARCH
    // ==============================
    // Route untuk fitur pencarian global di seluruh sistem
    Route::get('/search', [SearchController::class, 'index'])
        ->name('search');


    // ==============================
    // USER ROUTES
    // ==============================
    // Route di bawah ini hanya bisa diakses oleh user dengan role 'user' (bukan IT Support)
    Route::middleware('role:user')->group(function () {

        // Melihat dan mengubah profil user
        Route::get('/profile', [UserDashboardController::class, 'profile'])
            ->name('user.profile');

        Route::post('/profile', [UserDashboardController::class, 'updateProfile'])
            ->name('user.profile.update');

        // Halaman dashboard utama untuk user
        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('user.dashboard');

        // Melihat daftar tiket keluhan milik user
        Route::get('/tickets', [UserTicketController::class, 'index'])
            ->name('user.tickets.index');

        // Menampilkan form pengajuan keluhan baru
        Route::get('/tickets/create', [UserTicketController::class, 'create'])
            ->name('user.tickets.create');

        // Menyimpan keluhan baru yang sudah diisi user
        Route::post('/tickets', [UserTicketController::class, 'store'])
            ->name('user.tickets.store');

        // Melihat detail tiket tertentu
        Route::get('/tickets/{ticket}', [UserTicketController::class, 'show'])
            ->name('user.tickets.show');

        // Mengirim komentar pada tiket
        Route::post('/tickets/{ticket}/comment', [UserTicketController::class, 'comment'])
            ->name('user.tickets.comment');

        // Melihat daftar artikel knowledge base yang sudah dipublish
        Route::get('/kb', [UserKbController::class, 'index'])
            ->name('user.kb.index');

        // Membaca detail artikel knowledge base
        Route::get('/kb/{article}', [UserKbController::class, 'show'])
            ->name('user.kb.show');
    });


    // ==============================
    // IT SUPPORT ROUTES
    // ==============================
    // Route di bawah ini hanya bisa diakses oleh user dengan role 'it_support'
    // Semua URL diawali dengan /support/
    Route::prefix('support')
        ->name('support.')
        ->middleware('role:it_support')
        ->group(function () {

        // Melihat dan mengubah profil IT Support
        Route::get('/profile', [SupportDashboardController::class, 'profile'])
            ->name('profile');

        Route::post('/profile', [SupportDashboardController::class, 'updateProfile'])
            ->name('profile.update');

        // Halaman dashboard utama untuk IT Support
        Route::get('/dashboard', [SupportDashboardController::class, 'index'])
            ->name('dashboard');

        // Melihat semua daftar tiket keluhan dari semua user
        Route::get('/tickets', [SupportTicketController::class, 'index'])
            ->name('tickets.index');

        // Mengunduh laporan tiket dalam format Excel/CSV
        Route::get('/tickets-export', [SupportTicketController::class, 'exportCsv'])
            ->name('tickets.export');

        // Melihat detail tiket tertentu
        Route::get('/tickets/{ticket}', [SupportTicketController::class, 'show'])
            ->name('tickets.show');

        // Meminta AI untuk memberi rekomendasi solusi pada tiket
        Route::post('/tickets/{ticket}/generate-ai', [SupportTicketController::class, 'generateAiRecommendation'])
            ->name('tickets.generate-ai');

        // Mengubah status tiket (Open, In Progress, Closed)
        Route::post('/tickets/{ticket}/status', [SupportTicketController::class, 'updateStatus'])
            ->name('tickets.status');

        // Menugaskan tiket ke salah satu IT Support
        Route::post('/tickets/{ticket}/assign', [SupportTicketController::class, 'assign'])
            ->name('tickets.assign');

        // Mengirim komentar/balasan pada tiket dari sisi IT Support
        Route::post('/tickets/{ticket}/comment', [SupportTicketController::class, 'comment'])
            ->name('tickets.comment');

        // Route CRUD untuk kelola artikel knowledge base (index, create, store, edit, update, destroy)
        Route::resource('kb', SupportKbController::class);

        // Halaman AI Assistant untuk melihat ringkasan dan analisis tiket
        Route::get('/ai', [AiAssistantController::class, 'index'])
            ->name('ai.index');

        // Meminta AI untuk merangkum tiket-tiket terbaru
        Route::post('/ai/summarize', [AiAssistantController::class, 'summarizeLatest'])
            ->name('ai.summarize');
    });
});