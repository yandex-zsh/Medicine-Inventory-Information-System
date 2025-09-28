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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('manufacturer');
            $table->integer('quantity');
            $table->integer('minimum_stock_level')->default(10);
            $table->decimal('unit_price', 10, 2);
            $table->date('expiry_date');
            $table->string('batch_number');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->boolean('is_public')->default(true);
            $table->text('symptoms_treated')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('sales_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
