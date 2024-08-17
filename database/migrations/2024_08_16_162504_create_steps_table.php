<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_id')->onDelete('cascade');
            $table->date('day');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->time('time');
            $table->decimal('cost', 8, 2)->nullable();
            $table->boolean('checked')->default(false);
            $table->json('images')->nullable();
            $table->string('google_maps_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
