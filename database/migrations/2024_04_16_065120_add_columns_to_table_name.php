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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('profile_pic')->nullable()->after('password');
            $table->string('gender')->nullable()->after('password');
            $table->string('role')->nullable()->after('password');
            $table->tinyInteger('status')->default(0)->after('password');
            $table->string('country')->nullable()->after('password');
            $table->timestamp('login_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::dropIfExists('phone');
            Schema::dropIfExists('profile_pic');
            Schema::dropIfExists('gender');
            Schema::dropIfExists('role');
            Schema::dropIfExists('status');
            Schema::dropIfExists('country');
            Schema::dropIfExists('login_time');
        });
    }
};
