<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Database cleanup command
Artisan::command('db:clean-all', function () {
    $this->info('🚀 Starting Database Cleanup...');
    
    // Get current counts
    $this->info('📊 Current Record Counts:');
    $this->line('Media Library: ' . \Spatie\MediaLibrary\MediaCollections\Models\Media::count() . ' records');
    $this->line('Venues: ' . \App\Models\Venue::count() . ' records');
    $this->line('About Slides: ' . \App\Models\AboutSlide::count() . ' records');
    $this->line('Blogs: ' . \App\Models\Blog::count() . ' records');
    $this->line('Testimonials: ' . \App\Models\Testimonial::count() . ' records');
    $this->line('Galleries: ' . \App\Models\Gallery::count() . ' records');
    $this->line('Contact Submissions: ' . \App\Models\ContactSubmission::count() . ' records');
    $this->line('Menus: ' . \App\Models\Menu::count() . ' records');
    $this->line('Sessions: ' . \Illuminate\Support\Facades\DB::table('sessions')->count() . ' records');
    
    $this->newLine();
    $this->warn('⚠️  WARNING: This will permanently delete ALL data from these tables!');
    
    if (!$this->confirm('Are you sure you want to continue?')) {
        $this->error('❌ Cleanup cancelled.');
        return 1;
    }
    
    $this->newLine();
    $this->info('🗑️  Starting cleanup process...');
    $this->newLine();
    
    try {
        // Clear all tables
        \Spatie\MediaLibrary\MediaCollections\Models\Media::truncate();
        $this->info('✅ Media Library cleared');
        
        \App\Models\Venue::truncate();
        $this->info('✅ Venues cleared');
        
        \App\Models\AboutSlide::truncate();
        $this->info('✅ About Slides cleared');
        
        \App\Models\Blog::truncate();
        $this->info('✅ Blogs cleared');
        
        \App\Models\Testimonial::truncate();
        $this->info('✅ Testimonials cleared');
        
        \App\Models\Gallery::truncate();
        $this->info('✅ Galleries cleared');
        
        \App\Models\ContactSubmission::truncate();
        $this->info('✅ Contact Submissions cleared');
        
        \App\Models\Menu::truncate();
        $this->info('✅ Menus cleared');
        
        \Illuminate\Support\Facades\DB::table('sessions')->truncate();
        $this->info('✅ Sessions cleared');
        
        // Clear cache
        $this->info('🧹 Clearing Cache...');
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('view:clear');
        $this->info('✅ Cache cleared');
        
        $this->newLine();
        $this->info('🎉 Database cleanup completed successfully!');
        $this->newLine();
        
        // Show final counts
        $this->info('📊 Final Record Counts:');
        $this->line('Media Library: ' . \Spatie\MediaLibrary\MediaCollections\Models\Media::count() . ' records');
        $this->line('Venues: ' . \App\Models\Venue::count() . ' records');
        $this->line('About Slides: ' . \App\Models\AboutSlide::count() . ' records');
        $this->line('Blogs: ' . \App\Models\Blog::count() . ' records');
        $this->line('Testimonials: ' . \App\Models\Testimonial::count() . ' records');
        $this->line('Galleries: ' . \App\Models\Gallery::count() . ' records');
        $this->line('Contact Submissions: ' . \App\Models\ContactSubmission::count() . ' records');
        $this->line('Menus: ' . \App\Models\Menu::count() . ' records');
        $this->line('Sessions: ' . \Illuminate\Support\Facades\DB::table('sessions')->count() . ' records');
        
        $this->newLine();
        $this->info('✨ All tables have been cleared and are ready for fresh data!');
        
    } catch (\Exception $e) {
        $this->error('❌ Error during cleanup: ' . $e->getMessage());
        return 1;
    }
    
    return 0;
})->purpose('Clean all data from database tables');
