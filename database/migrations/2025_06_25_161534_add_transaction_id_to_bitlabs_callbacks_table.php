<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bitlabs_callbacks', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bitlabs_callbacks', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_id')->nullable()->after('tx');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
        });
    }
};
