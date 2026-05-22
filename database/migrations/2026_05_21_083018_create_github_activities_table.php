<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('github_activities', function (Blueprint $table) {
            $table->id();
            $table->string('github_event_id')->unique();
            $table->string('username');
            $table->string('type');
            $table->date('date');
            $table->boolean('is_private');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['username', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('github_activities');
    }
};
