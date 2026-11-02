<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Kitloom\AdminMenu\Contracts\MenuOverrides;
use Kitloom\AdminMenu\MenuEditor;
use Kitloom\AdminMenu\MenuOverride;
use Kitloom\AdminMenu\Nova\Support\MenuIdentity;
use Kitloom\Redirects\Nova\Settings\RedirectSettingsFields;
use Kitloom\Seo\Nova\Tools\SeoSettings;

/**
 * Redirection Settings from kitloom/redirects-nova go to SEO after the rules
 * and the 404 log (`arrange_admin_menu_like_wordpress` moved those), as in
 * Rank Math. The Redirects section is left empty and Nova does not show it.
 *
 * If someone has already placed the item, touches nothing.
 */
return new class extends Migration
{
    private const ATTRIBUTES = ['parent' => SeoSettings::class];

    public function up(): void
    {
        $overrides = app(MenuOverrides::class);

        // Without kitloom/redirects-nova (left out at install) there is nothing to move.
        if (! class_exists(RedirectSettingsFields::class)
            || $overrides->find(MenuOverrides::NOVA, MenuIdentity::forPath('/resources/redirections')) === null || $overrides->find(MenuOverrides::NOVA, $this->settings()) !== null) {
            return;
        }

        app(MenuEditor::class)->put(MenuOverrides::NOVA, $this->settings(), self::ATTRIBUTES);
    }

    public function down(): void
    {
        if (! class_exists(RedirectSettingsFields::class)) {
            return;
        }

        $stored = app(MenuOverrides::class)->find(MenuOverrides::NOVA, $this->settings())?->toArray();

        if ($stored !== null && $stored == (new MenuOverride($this->settings()))->with(self::ATTRIBUTES)->toArray()) {
            app(MenuEditor::class)->forget(MenuOverrides::NOVA, $this->settings());
        }
    }

    private function settings(): string
    {
        return MenuIdentity::forPath('settings/'.RedirectSettingsFields::PATH);
    }
};
