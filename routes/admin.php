<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\AboutSlideController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\Admin\MenuController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Media Library
    Route::get('media', [MediaLibraryController::class, 'index'])->name('media.index');
    Route::post('media/upload', [MediaLibraryController::class, 'upload'])->name('media.upload');
    Route::delete('media/{media}', [MediaLibraryController::class, 'destroy'])->name('media.destroy');
    Route::post('media/batch-delete', [MediaLibraryController::class, 'batchDelete'])->name('media.batch-delete');
    Route::post('media/regenerate-conversions', [MediaLibraryController::class, 'regenerateConversions'])->name('media.regenerate-conversions');
    Route::get('media/picker', [MediaLibraryController::class, 'picker'])->name('media.picker');
    
    // About Slides
    Route::resource('about-slides', AboutSlideController::class);
    Route::post('about-slides/reorder', [AboutSlideController::class, 'reorder'])->name('about-slides.reorder');
    
    // Testimonials
    Route::resource('testimonials', TestimonialController::class);
    
    // Galleries
    Route::resource('galleries', GalleryController::class);
    
    // Venues
    Route::resource('venues', VenueController::class);
    
    // Blogs
    Route::resource('blogs', BlogController::class);
    
    // Menu Management
    Route::resource('menus', MenuController::class);
    Route::post('menus/reorder', [MenuController::class, 'reorder'])->name('menus.reorder');
    
    // Contact Submissions
    Route::resource('contact-submissions', ContactSubmissionController::class)->only(['index', 'show', 'destroy']);
    Route::patch('contact-submissions/{contactSubmission}/read', [ContactSubmissionController::class, 'markAsRead'])->name('contact-submissions.read');
    Route::post('contact-submissions/mark-all-read', [ContactSubmissionController::class, 'markAllAsRead'])->name('contact-submissions.mark-all-read');
});
