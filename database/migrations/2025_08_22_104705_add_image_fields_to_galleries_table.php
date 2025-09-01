<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->unsignedBigInteger('cover_image_id')->nullable()->after('is_active');
            $table->json('gallery_image_ids')->nullable()->after('cover_image_id');
            
            $table->foreign('cover_image_id')->references('id')->on('media')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropForeign(['cover_image_id']);
            $table->dropColumn(['cover_image_id', 'gallery_image_ids']);
        });
    }
};
