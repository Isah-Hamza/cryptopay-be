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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->float('amount');
            $table->float('profit')->default(0);
            $table->string('proof')->nullable();
            $table->tinyInteger('transaction_type')->default(1); // 1 is Funding, 2 is Withdrawal
            $table->tinyInteger('status')->default(1); // 1 Pending, 2 Approved, 3 Rejected
            $table->tinyInteger('computed')->default(0); // 0 false, 1 true
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
