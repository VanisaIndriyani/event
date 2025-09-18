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
        Schema::create('event_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('field_name'); // nama field (contoh: nama_lengkap, no_hp)
            $table->string('field_label'); // label yang ditampilkan (contoh: Nama Lengkap, No HP)
            $table->enum('field_type', ['text', 'email', 'number', 'textarea', 'select', 'file', 'date']);
            $table->json('field_options')->nullable(); // untuk select options atau validasi
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['event_id', 'field_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_form_fields');
    }
};
