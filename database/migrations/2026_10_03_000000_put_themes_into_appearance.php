<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Kitloom\AdminMenu\Contracts\MenuOverrides;
use Kitloom\AdminMenu\MenuEditor;
use Kitloom\AdminMenu\MenuOverride;
use Kitloom\AdminMenu\Nova\Support\MenuIdentity;
use Kitloom\Site\Nova\ThemesTool;

/**
 * Themes from kitloom/site-nova — the first Appearance item, as in WordPress.
 * The skeleton builds the Appearance section itself (`arrange_admin_menu_like_wordpress`),
 * while the package brings its own: its item moves over, Nova doesn't render an empty section.
 *
 * If someone has already placed the Themes item or there is no Appearance in the menu,
 * touches nothing.
 */
return new class extends Migration
{
    private const APPEARANCE = MenuIdentity::CUSTOM_PREFIX.'wordpress-appearance';

    private const ATTRIBUTES = ['parent' => self::APPEARANCE, 'sort' => 300];

    public function up(): void
    {
        $overrides = app(MenuOverrides::class);

        if ($overrides->find(MenuOverrides::NOVA, self::APPEARANCE) === null || $overrides->find(MenuOverrides::NOVA, $this->themes()) !== null) {
            return;
        }

        app(MenuEditor::class)->put(MenuOverrides::NOVA, $this->themes(), self::ATTRIBUTES);
    }

    public function down(): void
    {
        $stored = app(MenuOverrides::class)->find(MenuOverrides::NOVA, $this->themes())?->toArray();

        if ($stored !== null && $stored == (new MenuOverride($this->themes()))->with(self::ATTRIBUTES)->toArray()) {
            app(MenuEditor::class)->forget(MenuOverrides::NOVA, $this->themes());
        }
    }

    private function themes(): string
    {
        return MenuIdentity::forPath(ThemesTool::PATH);
    }
};
