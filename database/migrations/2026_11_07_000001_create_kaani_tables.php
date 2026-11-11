<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('latitude', 8, 5);
            $table->decimal('longitude', 8, 5);
            $table->decimal('temp_c', 4, 1)->nullable();
            $table->decimal('rainfall_mm', 6, 1)->nullable();
            $table->unsignedTinyInteger('humidity')->nullable();
            $table->timestamp('weather_updated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('advisories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('municipality_id')->constrained()->cascadeOnDelete();
            $table->string('crop');
            $table->string('risk_level');
            $table->text('message');
            $table->string('status')->default('pending');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->string('review_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advisories');
        Schema::dropIfExists('municipalities');
    }
};
