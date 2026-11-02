<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Kitloom\AdminMenu\Contracts\MenuOverrides;
use Kitloom\AdminMenu\MenuEditor;
use Kitloom\AdminMenu\MenuOverride;
use Kitloom\AdminMenu\Nova\Support\MenuIdentity;

/**
 * Settings pages moved from `/nova/nova-options/{page}` to `/nova/settings/{page}`
 * with the rewritten kitloom/settings-nova. Admin menu edits made for the old
 * addresses — the item's own place, the items placed under it, a custom link —
 * follow them, so the menu stays as it was arranged.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->move('nova-options/', 'settings/');
    }

    public function down(): void
    {
        $this->move('settings/', 'nova-options/');
    }

    private function move(string $from, string $to): void
    {
        $old = MenuIdentity::forPath($from);
        $new = MenuIdentity::forPath($to);
        $rename = fn (?string $value, string $prefix, string $replacement): ?string => $value !== null && str_starts_with($value, $prefix)
            ? $replacement.substr($value, strlen($prefix))
            : $value;

        $all = app(MenuOverrides::class)->all(MenuOverrides::NOVA);
        $moved = [];

        foreach ($all as $override) {
            $attributes = $override->toArray();
            $identity = (string) $rename($override->key, $old, $new);

            $attributes['parent'] = $rename($attributes['parent'], $old, $new);

            // A custom link keeps its address inside `custom`.
            if ($attributes['custom'] !== null) {
                $attributes['custom']['path'] = $rename($rename($attributes['custom']['path'], $from, $to), '/'.$from, '/'.$to);
            }

            $moved[$identity] = MenuOverride::fromArray($identity, $attributes);
        }

        if (array_map(static fn (MenuOverride $o): array => $o->toArray(), $moved) == array_map(static fn (MenuOverride $o): array => $o->toArray(), $all)) {
            return;
        }

        app(MenuEditor::class)->replace(MenuOverrides::NOVA, $moved);
    }
};
