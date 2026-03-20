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
        Schema::create('fiscals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->foreign()->references('id')->on('products')->onDelete('cascade');
            $table->string('cProd')->nullable();
            $table->string('cEAN')->nullable();
            $table->string('xProd')->nullable();
            $table->string('NCM')->nullable();
            $table->string('CEST')->nullable();
            $table->string('CFOP')->nullable();
            $table->string('cEANTrib')->nullable();
            $table->string('CST')->nullable();
            $table->string('pST')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiscals');
    }
};
