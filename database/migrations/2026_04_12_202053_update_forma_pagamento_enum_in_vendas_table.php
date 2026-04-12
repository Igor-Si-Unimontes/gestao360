<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE vendas MODIFY COLUMN forma_pagamento ENUM('DINHEIRO','PIX','CARTAO_DEBITO','CARTAO_CREDITO') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE vendas MODIFY COLUMN forma_pagamento ENUM('DINHEIRO','PIX','CARTAO') NULL");
    }
};
