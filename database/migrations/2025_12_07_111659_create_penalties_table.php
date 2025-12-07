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
       Schema::create('penalties', function (Blueprint $table) {
    $table->id();
    $table->integer('user_id');
    $table->string('type'); 
    $table->date('date'); // date of the penalty
    $table->integer('minutes')->nullable(); // optional for time-based penalties
    $table->decimal('amount', 8, 2); 
    $table->text('notes')->nullable();
     $table->softDeletes();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalties');
    }
};
