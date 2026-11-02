<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Kitloom\AdminMenu\Contracts\MenuOverrides;
use Kitloom\AdminMenu\MenuEditor;
use Kitloom\AdminMenu\MenuOverride;
use Kitloom\AdminMenu\Nova\Support\MenuIdentity;
use Kitloom\Site\Nova\Customize;

/**
 * Customize from kitloom/site-nova — right after Themes in Appearance, as in
 * WordPress. The skeleton builds the Appearance section itself
 * (`arrange_admin_menu_like_wordpress`); the package's item would otherwise
 * open a second Appearance section of its own.
 *
 * If someone has already placed the Customize item or there is no Appearance in the menu,
 * touches nothing.
 */
return new class extends Migration
{
    private const APPEARANCE = MenuIdentity::CUSTOM_PREFIX.'wordpress-appearance';

    // Between Themes (300) and Menus (200).
    private const ATTRIBUTES = ['parent' => self::APPEARANCE, 'sort' => 250];

    public function up(): void
    {
        $overrides = app(MenuOverrides::class);

        if ($overrides->find(MenuOverrides::NOVA, self::APPEARANCE) === null || $overrides->find(MenuOverrides::NOVA, $this->customize()) !== null) {
            return;
        }

        app(MenuEditor::class)->put(MenuOverrides::NOVA, $this->customize(), self::ATTRIBUTES);
    }

    public function down(): void
    {
        $stored = app(MenuOverrides::class)->find(MenuOverrides::NOVA, $this->customize())?->toArray();

        if ($stored !== null && $stored == (new MenuOverride($this->customize()))->with(self::ATTRIBUTES)->toArray()) {
            app(MenuEditor::class)->forget(MenuOverrides::NOVA, $this->customize());
        }
    }

    private function customize(): string
    {
        return MenuIdentity::forPath(Customize::PATH);
    }
};
