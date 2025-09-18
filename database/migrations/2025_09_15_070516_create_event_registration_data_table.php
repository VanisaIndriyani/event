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
        Schema::create('event_registration_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_registration_id');
            $table->unsignedBigInteger('event_form_field_id');
            $table->text('field_value'); // nilai yang diisi user
            $table->string('file_path')->nullable(); // untuk field type file
            $table->timestamps();
            
            $table->unique(['event_registration_id', 'event_form_field_id'], 'reg_data_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registration_data');
    }
};
