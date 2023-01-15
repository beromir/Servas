<?php

use App\Models\Dashboard;
use App\Models\Link;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('settings')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('dashboard_columns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignIdFor(Dashboard::class)->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['id', 'dashboard_id']);
        });

        Schema::create('dashboard_linkables', function (Blueprint $table) {
            $table->foreignId('dashboard_column_id')->constrained('dashboard_columns')->cascadeOnDelete();
            $table->foreignIdFor(Link::class)->constrained()->cascadeOnDelete();

            $table->unique(['dashboard_column_id', 'link_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_linkables');
        Schema::dropIfExists('dashboard_columns');
        Schema::dropIfExists('dashboards');
    }
};
