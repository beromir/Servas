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
        Schema::create('public_links', function (Blueprint $table) {
            $table->id();

            $table->string('share_id')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('public_linkable');

            $table->timestamps();

            $table->unique(['public_linkable_type', 'public_linkable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_links');
    }
};
