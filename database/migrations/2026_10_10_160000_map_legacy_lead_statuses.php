<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('leads')->where('status', 'reviewed')->update(['status' => 'contacted']);
        DB::table('leads')->where('status', 'closed')->update(['status' => 'lost']);
    }

    public function down(): void
    {
        DB::table('leads')->where('status', 'contacted')->update(['status' => 'reviewed']);
        DB::table('leads')->where('status', 'lost')->update(['status' => 'closed']);
    }
};
