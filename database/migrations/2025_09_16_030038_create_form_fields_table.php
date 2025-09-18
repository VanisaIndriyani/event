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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('field_name'); // nama field (contoh: phone, address, etc)
            $table->string('field_label'); // label yang ditampilkan
            $table->enum('field_type', ['text', 'email', 'number', 'textarea', 'select', 'radio', 'checkbox', 'date', 'file']);
            $table->json('field_options')->nullable(); // untuk select, radio, checkbox
            $table->text('field_placeholder')->nullable();
            $table->text('field_description')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('field_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['event_id', 'field_order']);
            $table->unique(['event_id', 'field_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
