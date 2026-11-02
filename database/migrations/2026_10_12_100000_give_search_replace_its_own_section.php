<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Kitloom\AdminMenu\Contracts\MenuOverrides;
use Kitloom\AdminMenu\MenuEditor;
use Kitloom\AdminMenu\MenuOverride;
use Kitloom\AdminMenu\Nova\Support\MenuIdentity;
use Kitloom\SearchReplace\Nova\Tools\SearchReplace;

/**
 * Search & Replace is a module with a section of its own, as in the Filament
 * admin: `arrange_admin_menu_like_wordpress` had moved its item into a Tools
 * section the skeleton built, so the item goes back and the section takes the
 * place right after where Tools stood. Tools held nothing else once Admin Menu
 * went to Appearance, so it goes too.
 *
 * Each row is touched only while it is still as that migration wrote it: what
 * an admin arranged stays.
 */
return new class extends Migration
{
    private const TOOLS = MenuIdentity::CUSTOM_PREFIX.'wordpress-tools';

    private const TOOLS_SECTION = ['sort' => 600, 'custom' => ['type' => 'section', 'path' => null], 'label' => 'Tools', 'icon' => 'wrench'];

    private const ITEM_IN_TOOLS = ['parent' => self::TOOLS, 'sort' => 200];

    private const SECTION = ['sort' => 550];

    public function up(): void
    {
        if ($this->untouched($this->replacements(), self::ITEM_IN_TOOLS)) {
            app(MenuEditor::class)->forget(MenuOverrides::NOVA, $this->replacements());
        }

        if ($this->untouched(self::TOOLS, self::TOOLS_SECTION) && $this->children(self::TOOLS) === []) {
            app(MenuEditor::class)->forget(MenuOverrides::NOVA, self::TOOLS);
        }

        if (app(MenuOverrides::class)->find(MenuOverrides::NOVA, SearchReplace::class) === null) {
            app(MenuEditor::class)->put(MenuOverrides::NOVA, SearchReplace::class, self::SECTION);
        }
    }

    public function down(): void
    {
        $overrides = app(MenuOverrides::class);

        if ($this->untouched(SearchReplace::class, self::SECTION)) {
            app(MenuEditor::class)->forget(MenuOverrides::NOVA, SearchReplace::class);
        }

        if ($overrides->find(MenuOverrides::NOVA, self::TOOLS) === null) {
            app(MenuEditor::class)->put(MenuOverrides::NOVA, self::TOOLS, self::TOOLS_SECTION);
        }

        if ($overrides->find(MenuOverrides::NOVA, $this->replacements()) === null) {
            app(MenuEditor::class)->put(MenuOverrides::NOVA, $this->replacements(), self::ITEM_IN_TOOLS);
        }
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function untouched(string $identity, array $attributes): bool
    {
        $stored = app(MenuOverrides::class)->find(MenuOverrides::NOVA, $identity)?->toArray();

        return $stored !== null && $stored == (new MenuOverride($identity))->with($attributes)->toArray();
    }

    /**
     * @return list<string>
     */
    private function children(string $parent): array
    {
        return array_keys(array_filter(
            app(MenuOverrides::class)->all(MenuOverrides::NOVA),
            fn (MenuOverride $override): bool => $override->parent === $parent,
        ));
    }

    private function replacements(): string
    {
        return MenuIdentity::forPath('/resources/replacements');
    }
};
