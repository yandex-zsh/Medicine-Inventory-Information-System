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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('license_number')->nullable();
            $table->text('address')->nullable();
            $table->enum('role', ['admin', 'pharmacist'])->default('pharmacist');
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('id_document')->nullable();
            $table->text('rejection_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) { $table->dropColumn('phone'); }
            if (Schema::hasColumn('users', 'license_number')) { $table->dropColumn('license_number'); }
            if (Schema::hasColumn('users', 'address')) { $table->dropColumn('address'); }
            if (Schema::hasColumn('users', 'role')) { $table->dropColumn('role'); }
            if (Schema::hasColumn('users', 'is_approved')) { $table->dropColumn('is_approved'); }
            if (Schema::hasColumn('users', 'approved_by')) { $table->dropColumn('approved_by'); }
            if (Schema::hasColumn('users', 'approved_at')) { $table->dropColumn('approved_at'); }
            if (Schema::hasColumn('users', 'id_document')) { $table->dropColumn('id_document'); }
            if (Schema::hasColumn('users', 'rejection_reason')) { $table->dropColumn('rejection_reason'); }
        });
    }
};
