<?php

/**
 * Media Library Debug Script
 * Identifies the cause of infinite loading issues
 */

echo "🔍 Media Library Debug Script\n";
echo "=============================\n\n";

// Check if Laravel is available
if (!file_exists('vendor/autoload.php')) {
    echo "❌ Error: Laravel not found. Please run this from your Laravel project root.\n";
    exit(1);
}

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📊 System Information:\n";
echo "Laravel Version: " . app()->version() . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Environment: " . config('app.env') . "\n";
echo "Debug Mode: " . (config('app.debug') ? 'ON' : 'OFF') . "\n";
echo "APP_URL: " . config('app.url') . "\n\n";

echo "🗄️  Database Status:\n";
try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connection: OK\n";
    
    // Check media table
    $mediaCount = \Spatie\MediaLibrary\MediaCollections\Models\Media::count();
    echo "📁 Media records: {$mediaCount}\n";
    
    // Check if media table exists
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('media');
    echo "📋 Media table exists: " . ($tableExists ? 'YES' : 'NO') . "\n";
    
} catch (\Exception $e) {
    echo "❌ Database connection: FAILED - " . $e->getMessage() . "\n";
}

echo "\n💾 Storage Status:\n";
try {
    $storagePath = storage_path('app/public');
    $publicPath = public_path('storage');
    
    echo "Storage path: {$storagePath}\n";
    echo "Public path: {$publicPath}\n";
    
    if (is_dir($storagePath)) {
        echo "✅ Storage directory: EXISTS\n";
        $storageWritable = is_writable($storagePath);
        echo "📝 Storage writable: " . ($storageWritable ? 'YES' : 'NO') . "\n";
    } else {
        echo "❌ Storage directory: MISSING\n";
    }
    
    if (is_link($publicPath)) {
        echo "✅ Storage link: EXISTS\n";
        $linkTarget = readlink($publicPath);
        echo "🔗 Link target: {$linkTarget}\n";
    } else {
        echo "❌ Storage link: MISSING\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Storage check failed: " . $e->getMessage() . "\n";
}

echo "\n🔧 Media Library Configuration:\n";
try {
    echo "Media disk: " . config('media-library.disk_name') . "\n";
    echo "Filesystem default: " . config('filesystems.default') . "\n";
    echo "Queue connection: " . config('media-library.queue_connection_name') . "\n";
    echo "Queue conversions: " . (config('media-library.queue_conversions_by_default') ? 'YES' : 'NO') . "\n";
    
} catch (\Exception $e) {
    echo "❌ Config check failed: " . $e->getMessage() . "\n";
}

echo "\n🌐 Route Status:\n";
try {
    $router = app('router');
    $mediaRoutes = $router->getRoutes()->get('admin.media.index');
    
    if ($mediaRoutes) {
        echo "✅ Media index route: EXISTS\n";
        echo "   Controller: " . $mediaRoutes->getActionName() . "\n";
        echo "   URI: " . $mediaRoutes->getUri() . "\n";
    } else {
        echo "❌ Media index route: MISSING\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Route check failed: " . $e->getMessage() . "\n";
}

echo "\n📁 File Permissions:\n";
try {
    $paths = [
        'storage' => storage_path(),
        'bootstrap/cache' => base_path('bootstrap/cache'),
        'public/storage' => public_path('storage'),
        'storage/app/public' => storage_path('app/public'),
        'storage/logs' => storage_path('logs')
    ];
    
    foreach ($paths as $name => $path) {
        if (file_exists($path)) {
            $writable = is_writable($path);
            $readable = is_readable($path);
            echo "{$name}: " . ($readable ? 'R' : '-') . ($writable ? 'W' : '-') . "\n";
        } else {
            echo "{$name}: MISSING\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Permission check failed: " . $e->getMessage() . "\n";
}

echo "\n🔍 Common Issues & Solutions:\n";
echo "1. If storage link is missing: php artisan storage:link\n";
echo "2. If permissions are wrong: chmod -R 755 storage bootstrap/cache\n";
echo "3. If caches are stale: php artisan cache:clear config:clear view:clear\n";
echo "4. If database is locked: check SQLite file permissions\n";
echo "5. If media table is empty: check if Media model is working\n";

echo "\n🎯 Next Steps:\n";
echo "1. Check browser console for JavaScript errors\n";
echo "2. Check Laravel logs: storage/logs/laravel.log\n";
echo "3. Verify .env configuration\n";
echo "4. Test with a simple media upload\n";

echo "\n✨ Debug completed!\n";
