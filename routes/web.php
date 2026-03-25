<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ArbreController;
use App\Http\Controllers\EspeceController;
use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;

// Import des contrôleurs Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ArbreController as AdminArbreController;
use App\Http\Controllers\Admin\ZoneController as AdminZoneController;
use App\Http\Controllers\Admin\EspeceController as AdminEspeceController;
use App\Http\Controllers\Admin\ActualiteController as AdminActualiteController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Zones publiques
Route::prefix('zones')->group(function () {
    Route::get('/', [ZoneController::class, 'index'])->name('zones.index');
    Route::get('/{slug}', [ZoneController::class, 'show'])->name('zones.show');
});

// Arbres publics
Route::prefix('arbres')->group(function () {
    Route::get('/', [ArbreController::class, 'index'])->name('arbres.index');
    Route::get('/{slug}', [ArbreController::class, 'show'])->name('arbres.show');
});

// Espèces publiques
Route::prefix('especes')->group(function () {
    Route::get('/', [EspeceController::class, 'index'])->name('especes.index');
    Route::get('/{slug}', [EspeceController::class, 'show'])->name('especes.show');
});

// Actualités publiques
Route::prefix('actualites')->group(function () {
    Route::get('/', [ActualiteController::class, 'index'])->name('actualites.index');
    Route::get('/{slug}', [ActualiteController::class, 'show'])->name('actualites.show');
});

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Newsletter
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

///////////////////////////////////////////////////////////
// Routes d'administration

// Routes publiques (authentification admin)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Routes protégées par middleware admin
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    // Dashboard (corrigé : appel du contrôleur)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Gestion des rôles (accessible seulement aux super admin et admin)
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);

    // CRUD Arbres
    Route::resource('arbres', AdminArbreController::class);
    Route::get('/arbres/export/excel', [AdminArbreController::class, 'exportExcel'])->name('arbres.export.excel');
    Route::get('/arbres/export/pdf', [AdminArbreController::class, 'exportPdf'])->name('arbres.export.pdf');


    // ⚠️ **NOUVELLES ROUTES POUR LES IMAGES DES ARBRES**
    Route::get('/arbres/{arbre}/images', [App\Http\Controllers\Admin\ArbreImageController::class, 'index'])->name('arbres.images.index');
    Route::post('/arbres/{arbre}/images', [App\Http\Controllers\Admin\ArbreImageController::class, 'upload'])->name('arbres.images.upload');
    Route::delete('/arbres/images/{image}', [App\Http\Controllers\Admin\ArbreImageController::class, 'destroy'])->name('arbres.images.delete');

    // CRUD Zones
    Route::resource('zones', AdminZoneController::class);
    Route::post('/zones/reorder', [AdminZoneController::class, 'reorder'])->name('zones.reorder');
    Route::get('/zones/export/excel', [AdminZoneController::class, 'exportExcel'])->name('zones.export.excel');
    Route::get('/zones/export/pdf', [AdminZoneController::class, 'exportPdf'])->name('zones.export.pdf');

    // CRUD Espèces
    Route::resource('especes', AdminEspeceController::class);
    Route::get('/especes/export/excel', [AdminEspeceController::class, 'exportExcel'])->name('especes.export.excel');
    Route::get('/especes/export/pdf', [AdminEspeceController::class, 'exportPdf'])->name('especes.export.pdf');

    // CRUD Actualités
    Route::resource('actualites', AdminActualiteController::class);
    Route::post('/actualites/{id}/toggle-status', [AdminActualiteController::class, 'toggleStatus'])->name('actualites.toggle-status');
    Route::post('/actualites/{id}/duplicate', [AdminActualiteController::class, 'duplicate'])->name('actualites.duplicate');
    Route::get('/actualites/export/excel', [AdminActualiteController::class, 'exportExcel'])->name('actualites.export.excel');
    Route::get('/actualites/export/pdf', [AdminActualiteController::class, 'exportPdf'])->name('actualites.export.pdf');

    // Gestion des contacts
    Route::resource('contacts', AdminContactController::class)->only(['index', 'show', 'destroy']);
    Route::post('/contacts/{id}/mark-as-read', [AdminContactController::class, 'markAsRead'])->name('contacts.mark-as-read');
    Route::post('/contacts/{id}/mark-as-unread', [AdminContactController::class, 'markAsUnread'])->name('contacts.mark-as-unread');
    Route::get('/contacts/{id}/reply', [AdminContactController::class, 'reply'])->name('contacts.reply');
    Route::post('/contacts/{id}/send-reply', [AdminContactController::class, 'sendReply'])->name('contacts.send-reply');
    Route::post('/contacts/mass-destroy', [AdminContactController::class, 'massDestroy'])->name('contacts.mass-destroy');
    Route::post('/contacts/mass-mark-as-read', [AdminContactController::class, 'massMarkAsRead'])->name('contacts.mass-mark-as-read');
    Route::get('/contacts/export/excel', [AdminContactController::class, 'exportExcel'])->name('contacts.export.excel');
    Route::get('/contacts/export/pdf', [AdminContactController::class, 'exportPdf'])->name('contacts.export.pdf');

    // Gestion de la newsletter
    Route::resource('newsletters', AdminNewsletterController::class);
    Route::post('/newsletters/{id}/unsubscribe', [AdminNewsletterController::class, 'unsubscribe'])->name('newsletters.unsubscribe');
    Route::post('/newsletters/{id}/reactivate', [AdminNewsletterController::class, 'reactivate'])->name('newsletters.reactivate');
    Route::post('/newsletters/mass-destroy', [AdminNewsletterController::class, 'massDestroy'])->name('newsletters.mass-destroy');
    Route::post('/newsletters/mass-unsubscribe', [AdminNewsletterController::class, 'massUnsubscribe'])->name('newsletters.mass-unsubscribe');
    Route::get('/newsletters/export/excel', [AdminNewsletterController::class, 'exportExcel'])->name('newsletters.export.excel');
    Route::get('/newsletters/export/pdf', [AdminNewsletterController::class, 'exportPdf'])->name('newsletters.export.pdf');
    Route::get('/newsletters/export/csv', [AdminNewsletterController::class, 'exportCsv'])->name('newsletters.export.csv');
    Route::post('/newsletters/send', [AdminNewsletterController::class, 'sendNewsletter'])->name('newsletters.send');

    // Profil administrateur
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::delete('/profile/delete-avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');

    // Paramètres
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general');
    Route::post('/settings/social', [SettingsController::class, 'updateSocial'])->name('settings.social');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/optimize', [SettingsController::class, 'optimize'])->name('settings.optimize');
    Route::post('/settings/test-email', [SettingsController::class, 'testEmail'])->name('settings.test-email');



    // Gestion des utilisateurs et rôles
    Route::resource('users', App\Http\Controllers\Admin\UserRoleController::class);
    Route::post('users/{user}/toggle-status', [App\Http\Controllers\Admin\UserRoleController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    Route::get('users/search/ajax', [App\Http\Controllers\Admin\UserRoleController::class, 'search'])
        ->name('users.search');
});
