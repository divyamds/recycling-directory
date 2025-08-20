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
    Schema::table('facilities', function (Blueprint $table) {
        $table->string('city')->after('street_address');
        $table->string('state')->after('city');
        $table->string('postal_code')->after('state');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('facilities', function (Blueprint $table) {
        $table->dropColumn(['city','state','postal_code']);
    });
}
};
