<?php
// ============================================================
// routes/web.php
// ============================================================
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCampaignController;
use App\Http\Controllers\Admin\AdminDonorController;

// ─── Public Routes ────────────────────────────────────────────────────────────

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes
Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login',   [LoginController::class, 'login']);
Route::post('/logout',  [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register',[RegisterController::class, 'register']);

// Public campaign browsing
Route::get('/campaigns',        [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');

// ─── Donor Routes (auth required) ─────────────────────────────────────────────

Route::middleware(['auth'])->group(function () {

    // Donor dashboard
    Route::get('/donor/dashboard',       [DonorController::class, 'dashboard'])->name('donor.dashboard');
    Route::get('/donor/profile',         [DonorController::class, 'profile'])->name('donor.profile');
    Route::put('/donor/profile',         [DonorController::class, 'updateProfile'])->name('donor.profile.update');
    Route::put('/donor/password',        [DonorController::class, 'changePassword'])->name('donor.password.update');
    Route::get('/donor/registrations',   [DonorController::class, 'registrations'])->name('donor.registrations');
    Route::get('/donor/history',         [DonorController::class, 'history'])->name('donor.history');
    Route::get('/donor/notifications',   [DonorController::class, 'notifications'])->name('donor.notifications');

    // Campaign registration
    Route::post('/campaigns/{campaign}/register', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::post('/registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Campaigns CRUD
    Route::get('/campaigns',                    [AdminCampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create',             [AdminCampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns',                   [AdminCampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}',         [AdminCampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigns/{campaign}/edit',    [AdminCampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}',         [AdminCampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}',      [AdminCampaignController::class, 'destroy'])->name('campaigns.destroy');

    // Donor management within campaigns
    Route::post('/registrations/{registration}/accept',  [AdminCampaignController::class, 'acceptDonor'])->name('registrations.accept');
    Route::post('/registrations/{registration}/reject',  [AdminCampaignController::class, 'rejectDonor'])->name('registrations.reject');
    Route::post('/registrations/{registration}/donated', [AdminCampaignController::class, 'markDonated'])->name('registrations.donated');

    // Notifications & Export
    Route::post('/campaigns/{campaign}/notify', [AdminCampaignController::class, 'sendNotifications'])->name('campaigns.notify');
    Route::get('/campaigns/{campaign}/export',  [AdminCampaignController::class, 'exportDonors'])->name('campaigns.export');

    // Donors management
    Route::get('/donors',                          [AdminDonorController::class, 'index'])->name('donors.index');
    Route::get('/donors/{user}',                   [AdminDonorController::class, 'show'])->name('donors.show');
    Route::post('/donors/{user}/toggle-eligibility',[AdminDonorController::class, 'toggleEligibility'])->name('donors.toggle-eligibility');
    Route::delete('/donors/{user}',                [AdminDonorController::class, 'destroy'])->name('donors.destroy');
});
