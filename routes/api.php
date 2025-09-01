<?php

use App\Http\Controllers\Api\AboutSlideController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\VenueController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\MenuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API routes for frontend consumption
Route::prefix('v1')->group(function () {
    // About Slides
    Route::get('about-slides', [AboutSlideController::class, 'index']);
    
    // Testimonials
    Route::get('testimonials', [TestimonialController::class, 'index']);
    
    // Galleries
    Route::get('galleries', [GalleryController::class, 'index']);
    Route::get('galleries/{gallery}', [GalleryController::class, 'show']);
    
    // Venues
    Route::get('venues', [VenueController::class, 'index']);
    Route::get('venues/{venue}', [VenueController::class, 'show']);
    
    // Blogs
    Route::get('blogs', [BlogController::class, 'index']);
    Route::get('blogs/{blog}', [BlogController::class, 'show']);
    
    // Contact Form
    Route::post('contact', [ContactController::class, 'store']);
    
    // Menu System
    Route::get('menu', [MenuController::class, 'index']);
    Route::get('menu/primary', [MenuController::class, 'primary']);
    Route::get('menu/footer', [MenuController::class, 'footer']);
    Route::get('menu/admin', [MenuController::class, 'admin']);
});
