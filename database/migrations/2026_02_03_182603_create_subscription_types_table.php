<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Monthly", "Quarterly", "Yearly"
            $table->string('slug')->unique(); // e.g., "monthly", "quarterly", "yearly"
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0); // percentage or fixed amount
            $table->integer('duration_days'); // e.g., 30 for monthly, 90 for quarterly, 365 for yearly
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_types');
    }
};
