<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('timezone')->default(config('app.timezone', 'UTC'));
            $table->string('primary_color')->nullable();
            $table->string('accent_color')->nullable();
            $table->timestamps();
        });

        $appName = config('app.name', 'MyFitness');
        $defaultEtablissementId = DB::table('etablissements')->insertGetId([
            'name' => "{$appName} - Etablissement principal",
            'slug' => Str::slug($appName) ?: 'etablissement-principal',
            'code' => 'DEFAULT',
            'email' => config('mail.from.address'),
            'phone' => null,
            'address' => null,
            'city' => null,
            'country' => null,
            'timezone' => config('app.timezone', 'UTC'),
            'primary_color' => '#2563eb',
            'accent_color' => '#0ea5e9',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->addForeignKey('users', $defaultEtablissementId);
        $this->addForeignKey('members', $defaultEtablissementId);
        $this->addForeignKey('payments', $defaultEtablissementId);
        $this->addForeignKey('classes', $defaultEtablissementId);
        $this->addForeignKey('rooms', $defaultEtablissementId);
        $this->addForeignKey('subscription_types', $defaultEtablissementId);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'subscription_types',
            'rooms',
            'classes',
            'payments',
            'members',
            'users',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'etablissement_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropConstrainedForeignId('etablissement_id');
            });
        }

        Schema::dropIfExists('etablissements');
    }

    private function addForeignKey(string $table, int $defaultEtablissementId): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        if (!Schema::hasColumn($table, 'etablissement_id')) {
            Schema::table($table, function (Blueprint $blueprint) use ($defaultEtablissementId) {
                $blueprint->foreignId('etablissement_id')
                    ->after('id')
                    ->default($defaultEtablissementId)
                    ->constrained('etablissements')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
            });
        }

        DB::table($table)
            ->whereNull('etablissement_id')
            ->update(['etablissement_id' => $defaultEtablissementId]);
    }
};
