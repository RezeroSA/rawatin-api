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
        Schema::create('otp_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 13);
            $table->string('otp');
            $table->datetimes('created_at');
            $table->datetimes('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
