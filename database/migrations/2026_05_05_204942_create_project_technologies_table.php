<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('project_technologies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->foreignId('technology_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_technologies');
    }
};
