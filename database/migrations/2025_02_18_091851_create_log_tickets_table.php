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
        Schema::create('log_tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('id_ticket');
            $table->integer('id_log');
            $table->string('assign_by');
            $table->string('assign_to_dept');
            $table->dateTime('assign_date');
            $table->dateTime('accept_date')->nullable();
            $table->integer('assign_status');
            $table->integer('preclosed_status');
            $table->dateTime('preclosed_date');
            $table->text('preclosed_message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_tickets');
    }
};
