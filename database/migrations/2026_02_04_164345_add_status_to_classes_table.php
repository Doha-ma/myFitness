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

        Schema::table('classes', function (Blueprint $table) {

            // Check if status column doesn't exist before adding it

            if (!Schema::hasColumn('classes', 'status')) {

                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('duration');

            }

            // Add rejection_reason column

            $table->text('rejection_reason')->nullable()->after('status');

        });

    }



    /**

     * Reverse the migrations.

     */

    public function down(): void

    {

        Schema::table('classes', function (Blueprint $table) {

            $table->dropColumn(['status', 'rejection_reason']);

        });

    }

};
