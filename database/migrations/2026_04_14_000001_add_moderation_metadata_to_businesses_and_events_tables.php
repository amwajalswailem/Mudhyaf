<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModerationMetadataToBusinessesAndEventsTables extends Migration
{
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejection_reason')->nullable()->after('approved_at');
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }

            if (! Schema::hasColumn('events', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('approved_at');
            }
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }

            if (Schema::hasColumn('events', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_by', 'approved_at', 'rejection_reason']);
        });
    }
}
