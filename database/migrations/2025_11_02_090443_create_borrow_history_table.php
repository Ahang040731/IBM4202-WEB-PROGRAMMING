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
        Schema::create('borrow_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignId('copy_id')->constrained('book_copies')->cascadeOnDelete();

            $table->timestamp('borrowed_at');
            $table->timestamp('due_at');
            $table->timestamp('returned_at')->nullable();

            $table->enum('status', ['active','overdue','returned','lost'])->default('active');
            $table->unsignedTinyInteger('extension_count')->default(0);
            $table->string('extension_reason')->nullable();
            $table->enum('approve_status', ['auto','approved','pending','rejected'])->default('pending');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_history');
    }
};
