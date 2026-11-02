<?php

declare(strict_types=1);

use App\Nova\Dashboards\Main;
use App\Nova\Tools\Users;
use Illuminate\Database\Migrations\Migration;
use Kitloom\AdminMenu\Contracts\MenuOverrides;
use Kitloom\AdminMenu\MenuEditor;
use Kitloom\AdminMenu\MenuOverride;
use Kitloom\AdminMenu\Nova\AdminMenuTool;
use Kitloom\AdminMenu\Nova\Support\MenuIdentity;
use Kitloom\CustomFields\Nova\CustomFieldsTool;
use Kitloom\Forms\Nova\Tools\ContactForms;
use Kitloom\Media\Nova\Tools\Media;
use Kitloom\Navigation\Nova\MenuBuilder;
use Kitloom\Seo\Nova\Tools\SeoSettings;
use Kitloom\SlideAnything\Nova\Tools\Sliders;
use Kitloom\Slider\Nova\Tools\Sliders as WpSliders;
use Kitloom\WpSchema\Nova\Tools\Comments;
use Kitloom\WpSchema\Nova\Tools\Pages;
use Kitloom\WpSchema\Nova\Tools\Posts;
use Kitloom\WpSchema\Nova\Tools\SiteSettings;

/**
 * The Nova sidebar in WordPress admin order: Dashboard, Posts, Media (with
 * WP Sliders under it), Pages, Comments, Appearance, Users, Tools, Settings, then
 * plugin sections — Contact Forms, SEO, Custom Fields, Sliders. Sections not listed here sink to
 * the bottom (`kitloom-admin-menu.default_sort` = 0).
 *
 * Items only move, nothing is hidden: Menus and Widgets go to
 * Appearance, Replacements (from Search & Replace) and Admin Menu to Tools,
 * Redirections and 404 Monitor to SEO (as in Rank Math).
 *
 * If someone has already arranged the menu, the migration leaves it alone.
 */
return new class extends Migration
{
    private const APPEARANCE = MenuIdentity::CUSTOM_PREFIX.'wordpress-appearance';

    private const TOOLS = MenuIdentity::CUSTOM_PREFIX.'wordpress-tools';

    public function up(): void
    {
        if (app(MenuOverrides::class)->all(MenuOverrides::NOVA) !== []) {
            return;
        }

        app(MenuEditor::class)->replace(MenuOverrides::NOVA, $this->overrides());
    }

    /**
     * Removes only the rows that are still as the migration wrote
     * them. On save the menu screen rewrites the sort of all items, and
     * a row with an admin's sort is their arrangement, not ours.
     */
    public function down(): void
    {
        $overrides = app(MenuOverrides::class);

        foreach ($this->overrides() as $identity => $attributes) {
            $stored = $overrides->find(MenuOverrides::NOVA, $identity)?->toArray();

            if ($stored !== null && $stored == (new MenuOverride($identity))->with($attributes)->toArray()) {
                app(MenuEditor::class)->forget(MenuOverrides::NOVA, $identity);
            }
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function overrides(): array
    {
        $topLevel = [
            Main::class => [],
            Posts::class => [],
            Media::class => [],
            WpSliders::class => [],
            Pages::class => [],
            Comments::class => [],
            self::APPEARANCE => ['custom' => ['type' => 'section', 'path' => null], 'label' => 'Appearance', 'icon' => 'paint-brush'],
            Users::class => [],
            self::TOOLS => ['custom' => ['type' => 'section', 'path' => null], 'label' => 'Tools', 'icon' => 'wrench'],
            SiteSettings::class => [],
            ContactForms::class => [],
            SeoSettings::class => [],
            CustomFieldsTool::class => [],
            Sliders::class => [],
        ];

        $overrides = [];
        $sort = count($topLevel) * 100;

        foreach ($topLevel as $identity => $attributes) {
            $overrides[$identity] = ['sort' => $sort] + $attributes;
            $sort -= 100;
        }

        return $overrides + [
            MenuBuilder::class => ['parent' => self::APPEARANCE, 'sort' => 200],
            MenuIdentity::forPath('settings/widgets') => ['parent' => self::APPEARANCE, 'sort' => 100],
            MenuIdentity::forPath('/resources/replacements') => ['parent' => self::TOOLS, 'sort' => 200],
            AdminMenuTool::class => ['parent' => self::TOOLS, 'sort' => 100],
            MenuIdentity::forPath('/resources/redirections') => ['parent' => SeoSettings::class],
            MenuIdentity::forPath('/resources/log404s') => ['parent' => SeoSettings::class],
        ];
    }
};
