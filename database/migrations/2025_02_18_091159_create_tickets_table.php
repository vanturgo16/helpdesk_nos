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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('no_ticket')->unique();
            $table->integer('seq_no');
            $table->string('priority');
            $table->string('category');
            $table->string('sub_category');
            $table->text('notes')->nullable();
            $table->dateTime('report_date');
            $table->string('created_by');
            $table->text('file_1')->nullable();
            $table->text('file_2')->nullable();
            $table->text('file_3')->nullable();
            $table->text('closed_notes')->nullable();
            $table->string('final_category')->nullable();
            $table->string('final_sub_category')->nullable();
            $table->dateTime('target_solved_date')->nullable();
            $table->dateTime('closed_date')->nullable();
            $table->text('duration')->nullable();
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
