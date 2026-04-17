<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE vendas MODIFY COLUMN status ENUM('ABERTA','EM_PREPARO','FINALIZADA','CANCELADA') NOT NULL DEFAULT 'FINALIZADA'");
        DB::statement("ALTER TABLE vendas MODIFY COLUMN tipo ENUM('RAPIDA','MESA','DELIVERY') NOT NULL DEFAULT 'RAPIDA'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE vendas MODIFY COLUMN status ENUM('ABERTA','FINALIZADA','CANCELADA') NOT NULL DEFAULT 'FINALIZADA'");
        DB::statement("ALTER TABLE vendas MODIFY COLUMN tipo ENUM('RAPIDA','MESA','DELIVERY') NOT NULL DEFAULT 'RAPIDA'");
    }
};
