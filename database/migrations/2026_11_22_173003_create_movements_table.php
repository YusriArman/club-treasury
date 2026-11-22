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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->date('occurred_on');
            $table->bigInteger('amount_sen');
            $table->string('description');
            $table->string('reference')->nullable();
            $table->foreignId('reverses_movement_id')->nullable()->constrained('movements');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
