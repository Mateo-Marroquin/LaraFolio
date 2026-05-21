<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('github_repositories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('github_repo_id'); //TODO: Debería ser unique cuando implementemos el login con github
            $table->string('name');
            $table->string('full_name');
            $table->string('html_url');
            $table->text('description')->nullable();
            $table->string('primary_language')->nullable();
            $table->integer('stars_count')->default(0);
            $table->integer('forks_count')->default(0);
            $table->boolean('is_fork')->default(false);
            $table->timestamp('github_updated_at')->nullable();
            $table->string('languages_url');
            $table->boolean('is_private');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('github_repositories');
    }
};
