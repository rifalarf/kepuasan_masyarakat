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
        Schema::table('unsurs', function (Blueprint $table) {
            // Tambahkan foreign key untuk village_id.
            // Nullable berarti unsur ini bersifat global (milik Admin Utama).
            // CascadeOnDelete berarti jika Satuan Kerja dihapus, unsurnya juga ikut terhapus.
            $table->foreignId('village_id')->nullable()->constrained('villages')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unsurs', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
            $table->dropColumn('village_id');
        });
    }
};
