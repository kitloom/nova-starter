<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Kitloom\AdminMenu\Contracts\MenuOverrides;
use Kitloom\AdminMenu\MenuEditor;
use Kitloom\AdminMenu\MenuOverride;
use Kitloom\AdminMenu\Nova\AdminMenuTool;
use Kitloom\AdminMenu\Nova\Support\MenuIdentity;

/**
 * Admin Menu goes to Appearance, right under Menus, where the Filament admin
 * keeps it: the site's menus and the admin's own side by side.
 * `arrange_admin_menu_like_wordpress` had put it in Tools; Nova sorts
 * descending, so 150 lands between Menus (200) and Widgets (100).
 *
 * Moves the item only while it is still where that migration left it: an
 * admin who placed it elsewhere keeps their choice.
 */
return new class extends Migration
{
    private const APPEARANCE = MenuIdentity::CUSTOM_PREFIX.'wordpress-appearance';

    private const IN_TOOLS = ['parent' => MenuIdentity::CUSTOM_PREFIX.'wordpress-tools', 'sort' => 100];

    private const UNDER_MENUS = ['parent' => self::APPEARANCE, 'sort' => 150];

    public function up(): void
    {
        if (app(MenuOverrides::class)->find(MenuOverrides::NOVA, self::APPEARANCE) === null) {
            return;
        }

        $this->move(self::IN_TOOLS, self::UNDER_MENUS);
    }

    public function down(): void
    {
        $this->move(self::UNDER_MENUS, self::IN_TOOLS);
    }

    /**
     * @param  array<string, mixed>  $from
     * @param  array<string, mixed>  $to
     */
    private function move(array $from, array $to): void
    {
        $stored = app(MenuOverrides::class)->find(MenuOverrides::NOVA, AdminMenuTool::class)?->toArray();

        if ($stored !== null && $stored == (new MenuOverride(AdminMenuTool::class))->with($from)->toArray()) {
            app(MenuEditor::class)->put(MenuOverrides::NOVA, AdminMenuTool::class, $to);
        }
    }
};
