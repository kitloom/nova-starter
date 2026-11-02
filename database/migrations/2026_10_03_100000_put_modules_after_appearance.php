<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Kitloom\AdminMenu\Contracts\MenuOverrides;
use Kitloom\AdminMenu\MenuEditor;
use Kitloom\AdminMenu\MenuOverride;
use Kitloom\Modules\Nova\ModulesTool;

/**
 * Modules from kitloom/modules-nova — between Appearance and Users, like Plugins in
 * WordPress. Section sorts are set by `arrange_admin_menu_like_wordpress`:
 * Appearance — 800, Users — 700.
 *
 * If someone has already placed the Modules section or the menu was not arranged by the migration,
 * touches nothing.
 */
return new class extends Migration
{
    private const APPEARANCE = 'menu_custom:wordpress-appearance';

    private const ATTRIBUTES = ['sort' => 750];

    public function up(): void
    {
        $overrides = app(MenuOverrides::class);

        if ($overrides->find(MenuOverrides::NOVA, self::APPEARANCE) === null || $overrides->find(MenuOverrides::NOVA, ModulesTool::class) !== null) {
            return;
        }

        app(MenuEditor::class)->put(MenuOverrides::NOVA, ModulesTool::class, self::ATTRIBUTES);
    }

    public function down(): void
    {
        $stored = app(MenuOverrides::class)->find(MenuOverrides::NOVA, ModulesTool::class)?->toArray();

        if ($stored !== null && $stored == (new MenuOverride(ModulesTool::class))->with(self::ATTRIBUTES)->toArray()) {
            app(MenuEditor::class)->forget(MenuOverrides::NOVA, ModulesTool::class);
        }
    }
};
