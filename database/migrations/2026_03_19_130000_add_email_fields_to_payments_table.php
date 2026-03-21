<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->timestamp('email_sent_at')->nullable()->after('notes');
            $table->enum('email_status', ['pending', 'sent', 'failed'])->default('pending')->after('email_sent_at');
            $table->text('email_error')->nullable()->after('email_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['email_sent_at', 'email_status', 'email_error']);
        });
    }
};
