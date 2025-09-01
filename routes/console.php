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

// VPS fix command
Artisan::command('vps:fix', function () {
    $this->info('🔧 Starting VPS Configuration Fix...');
    
    // Show current configuration
    $this->info('📊 Current Configuration:');
    $this->line('APP_URL: ' . config('app.url'));
    $this->line('FILESYSTEM_DISK: ' . config('filesystems.default'));
    $this->line('MEDIA_DISK: ' . config('media-library.disk_name'));
    $this->line('DB_CONNECTION: ' . config('database.default'));
    
    $this->newLine();
    $this->warn('⚠️  This will fix storage links, permissions, and clear caches!');
    
    if (!$this->confirm('Are you sure you want to continue?')) {
        $this->error('❌ Fix cancelled.');
        return 1;
    }
    
    try {
        // 1. Clear all caches
        $this->info('1️⃣ Clearing caches...');
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('view:clear');
        $this->call('route:clear');
        $this->info('   ✅ Caches cleared');
        
        // 2. Fix storage link
        $this->info('2️⃣ Fixing storage link...');
        if (is_link(public_path('storage'))) {
            unlink(public_path('storage'));
            $this->info('   ✅ Old storage link removed');
        }
        
        $this->call('storage:link');
        $this->info('   ✅ Storage link created');
        
        // 3. Clear media library cache
        $this->info('3️⃣ Clearing media library cache...');
        try {
            $this->call('media:clear');
            $this->info('   ✅ Media library cache cleared');
        } catch (\Exception $e) {
            $this->warn('   ⚠️  Media clear command not available: ' . $e->getMessage());
        }
        
        // 4. Test configuration
        $this->info('4️⃣ Testing configuration...');
        
        // Test database connection
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            $this->info('   ✅ Database connection: OK');
        } catch (\Exception $e) {
            $this->error('   ❌ Database connection: FAILED - ' . $e->getMessage());
        }
        
        // Test storage
        try {
            $testFile = storage_path('app/public/test.txt');
            file_put_contents($testFile, 'test');
            unlink($testFile);
            $this->info('   ✅ Storage: OK');
        } catch (\Exception $e) {
            $this->error('   ❌ Storage: FAILED - ' . $e->getMessage());
        }
        
        $this->newLine();
        $this->info('🎉 VPS Configuration Fix Completed!');
        
        $this->newLine();
        $this->info('📋 Next Steps:');
        $this->line('1. Update your .env file with APP_URL=https://cms.thebase11.com');
        $this->line('2. Set FILESYSTEM_DISK=public');
        $this->line('3. Set MEDIA_DISK=public');
        $this->line('4. Restart your web server');
        $this->line('5. Clear browser cache and test');
        
        $this->newLine();
        $this->info('🔗 Test URLs:');
        $this->line('Storage: ' . config('app.url') . '/storage');
        $this->line('Media: ' . config('app.url') . '/storage/media-library');
        
    } catch (\Exception $e) {
        $this->error('❌ Error during fix: ' . $e->getMessage());
        return 1;
    }
    
    return 0;
})->purpose('Fix VPS configuration issues');
