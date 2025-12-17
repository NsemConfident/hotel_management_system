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
        Schema::table('guests', function (Blueprint $table) {
            $table->boolean('is_vip')->default(false)->after('address');
            $table->unsignedInteger('loyalty_points')->default(0)->after('is_vip');
            $table->date('date_of_birth')->nullable()->after('loyalty_points');
            $table->string('nationality')->nullable()->after('date_of_birth');
            $table->string('preferred_language')->nullable()->after('nationality');
            $table->json('preferences')->nullable()->after('preferred_language'); // Store room preferences, dietary, etc.
            $table->text('special_requests')->nullable()->after('preferences');
            $table->text('notes')->nullable()->after('special_requests');
            $table->timestamp('last_visit_at')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn([
                'is_vip',
                'loyalty_points',
                'date_of_birth',
                'nationality',
                'preferred_language',
                'preferences',
                'special_requests',
                'notes',
                'last_visit_at',
            ]);
        });
    }
};
