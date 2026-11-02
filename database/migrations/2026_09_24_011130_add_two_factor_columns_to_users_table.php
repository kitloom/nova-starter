<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

/**
 * Two-factor sign-in is off (`NovaServiceProvider::fortify()`). The users
 * are WordPress's table, which gets no columns of Laravel's: when it is
 * turned on, its secrets go to user meta.
 */
return new class extends Migration
{
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
};
