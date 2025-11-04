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
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // +ve for top-up, -ve for deductions
            $table->decimal('delta', 10, 2); // allow negative values

            // optional but very useful metadata
            $table->enum('reason', ['topup','fine','lost','damage','activate','manual'])->default('manual');
            $table->enum('method', ['credit','card','online_banking','tng','cash','system'])->nullable(); // 'system' for auto entries
            $table->string('reference')->nullable(); // receipt / gateway ref / note

            $table->timestamp('created_at')->useCurrent();
            // no updated_at needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
    }
};
