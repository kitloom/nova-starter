<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Kitloom\AdminMenu\Contracts\MenuOverrides;
use Kitloom\AdminMenu\MenuEditor;
use Kitloom\AdminMenu\MenuOverride;
use Kitloom\AdminMenu\Nova\Support\MenuIdentity;

/**
 * kitloom/redirects-nova now ends its section with "How it works". This site
 * moved everything else of the section into SEO (`put_redirect_settings_into_seo`),
 * so the item would keep an otherwise empty Redirects section on screen; it is
 * hidden instead, and the README stays on the Modules screen.
 *
 * If someone has already placed the item, touches nothing.
 */
return new class extends Migration
{
    private const ATTRIBUTES = ['hidden' => true];

    public function up(): void
    {
        $overrides = app(MenuOverrides::class);

        if ($overrides->find(MenuOverrides::NOVA, MenuIdentity::forPath('/resources/redirections')) === null || $overrides->find(MenuOverrides::NOVA, $this->help()) !== null) {
            return;
        }

        app(MenuEditor::class)->put(MenuOverrides::NOVA, $this->help(), self::ATTRIBUTES);
    }

    public function down(): void
    {
        $stored = app(MenuOverrides::class)->find(MenuOverrides::NOVA, $this->help())?->toArray();

        if ($stored !== null && $stored == (new MenuOverride($this->help()))->with(self::ATTRIBUTES)->toArray()) {
            app(MenuEditor::class)->forget(MenuOverrides::NOVA, $this->help());
        }
    }

    private function help(): string
    {
        return MenuIdentity::forPath('/modules/kitloom/redirects');
    }
};
