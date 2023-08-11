<?php

use App\Http\Controllers\AcceptOrganizationInvitationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Resources\OrganizationInvitationResource\Pages\AcceptInvitation;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/signin', function () {
    return view('pages/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profilee', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('organization-invitations/{record}/accept', AcceptInvitation::class)
    ->middleware(['signed'])
    ->name('organization-invitations.accept');

require __DIR__.'/auth.php';
