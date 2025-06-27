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
        Schema::create('bitlabs_callbacks', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string( 'transaction_id');
            $table->string('ip')->nullable();
            $table->string('offer_value')->nullable();
            $table->string('offer_name')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitlabs_callbacks');
    }
};
