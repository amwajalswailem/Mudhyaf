<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommendationTables extends Migration
{
    public function up()
    {
        Schema::create('recommendation_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('entity_type', 191);
            $table->unsignedBigInteger('entity_id');
            $table->decimal('score', 8, 4);
            $table->json('reason')->nullable();
            $table->timestamp('generated_at')->nullable()->useCurrent();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->index('user_id');
            $table->index(['entity_type', 'entity_id']);
            $table->index('generated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('recommendation_logs');
    }
}
