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
        Schema::create('message_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('category');
            $table->string('language');
            $table->string('status')->default('PENDING'); // APPROVED, REJECTED, PENDING
            $table->longText('content')->nullable(); // JSON structure of components
            $table->string('whatsapp_template_id')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'name', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_templates');
    }
};
