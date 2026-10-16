<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->dateTime('delivered_at');
            $table->text('note');
            $table->timestamps();

            $table->index(['project_id', 'delivered_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_deliveries');
    }
};
