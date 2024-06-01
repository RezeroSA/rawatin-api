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
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('service_id');
            $table->double('service_fee');
            $table->double('transport_fee');
            $table->double('total');
            $table->string('payment_method');
            $table->string('officer_id')->nullable();
            $table->enum('status', ['waiting', 'on process', 'complete', 'canceled'])->default('waiting');
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
