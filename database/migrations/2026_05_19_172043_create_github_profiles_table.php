<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('github_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->onDelete('cascade');
            $table->string('github_id')->unique();
            $table->string('username')->unique();
            $table->string('name')->nullable();
            $table->string('avatar_url')->nullable();
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
            $table->integer('public_repos')->default(0);
            $table->integer('followers')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('github_profiles');
    }
};
