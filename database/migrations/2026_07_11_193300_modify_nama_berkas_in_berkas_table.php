<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify enum to varchar(50) to allow kps and kip
        DB::statement("ALTER TABLE berkas MODIFY COLUMN nama_berkas VARCHAR(50)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE berkas MODIFY COLUMN nama_berkas ENUM('ijazah', 'kk', 'akta', 'ktp orang tua')");
    }
};
