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
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('school_id', 6)->unique()->nullable()->after('lrn')->comment('6-digit school ID: YYNNNN (YY=year, NNNN=seq)');
            $table->string('id_photo_path')->nullable()->after('address')->comment('Path to uploaded ID photo');
            $table->string('signature_path')->nullable()->after('id_photo_path')->comment('Path to student signature');
            $table->index('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropIndex(['school_id']);
            $table->dropColumn(['school_id', 'id_photo_path', 'signature_path']);
        });
    }
};
