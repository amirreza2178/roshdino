<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->unique(['type', 'slug']);
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('status', 20);
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->json('benefits');
            $table->json('process');
            $table->string('cta_label');
            $table->string('cta_url')->nullable();
            $table->string('interest', 20)->nullable();
            $table->string('status', 20);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('summary')->nullable();
            $table->string('slug')->unique();
            $table->text('problem');
            $table->text('solution');
            $table->json('technologies');
            $table->text('result')->nullable();
            $table->string('status', 20);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('disk', 40);
            $table->string('path');
            $table->string('original_name');
            $table->string('mime', 80);
            $table->unsignedInteger('size');
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->timestamps();
        });

        Schema::create('work_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_id')->constrained()->restrictOnDelete();
            $table->string('alt')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('seo_metas', function (Blueprint $table) {
            $table->id();
            $table->string('seoable_type');
            $table->unsignedBigInteger('seoable_id');
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 200)->nullable();
            $table->string('canonical')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description', 300)->nullable();
            $table->foreignId('og_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->boolean('indexable')->default(true);
            $table->timestamps();

            $table->unique(['seoable_type', 'seoable_id']);
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_metas');
        Schema::dropIfExists('work_images');
        Schema::dropIfExists('media');
        Schema::dropIfExists('works');
        Schema::dropIfExists('services');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('site_settings');
    }
};
