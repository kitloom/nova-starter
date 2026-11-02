<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Kitloom\AdminMenu\Contracts\MenuOverrides;
use Kitloom\AdminMenu\MenuEditor;
use Kitloom\AdminMenu\MenuOverride;
use Kitloom\Shortcodes\Nova\ShortcodesTool;

/**
 * kitloom/shortcodes-nova moved its page from Settings to a Tools section of
 * its own: the section takes the place WordPress gives Tools — after Users
 * (700), before Search & Replace (550) and Settings (500), where the
 * skeleton's own Tools section stood until `give_search_replace_its_own_section`.
 *
 * Touches nothing if the menu was not arranged by the migrations, the
 * section is already placed, or the skeleton's Tools section is still
 * there (an admin kept it): two sections named Tools would only confuse.
 */
return new class extends Migration
{
    private const APPEARANCE = 'menu_custom:wordpress-appearance';

    private const TOOLS = 'menu_custom:wordpress-tools';

    private const ATTRIBUTES = ['sort' => 600];

    public function up(): void
    {
        $overrides = app(MenuOverrides::class);

        if ($overrides->find(MenuOverrides::NOVA, self::APPEARANCE) === null
            || $overrides->find(MenuOverrides::NOVA, self::TOOLS) !== null
            || $overrides->find(MenuOverrides::NOVA, ShortcodesTool::class) !== null) {
            return;
        }

        app(MenuEditor::class)->put(MenuOverrides::NOVA, ShortcodesTool::class, self::ATTRIBUTES);
    }

    public function down(): void
    {
        $stored = app(MenuOverrides::class)->find(MenuOverrides::NOVA, ShortcodesTool::class)?->toArray();

        if ($stored !== null && $stored == (new MenuOverride(ShortcodesTool::class))->with(self::ATTRIBUTES)->toArray()) {
            app(MenuEditor::class)->forget(MenuOverrides::NOVA, ShortcodesTool::class);
        }
    }
};
