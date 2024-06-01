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
        Schema::create('recovery_otp', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 13);
            $table->string('otp');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recovery_otp');
    }
};
