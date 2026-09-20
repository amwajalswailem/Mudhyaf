<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropUserInteractionsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('user_interactions');
    }

    public function down()
    {
        Schema::create('user_interactions', function ($table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('entity_type', 191);
            $table->unsignedBigInteger('entity_id');
            $table->string('interaction_type', 50);
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }
}
