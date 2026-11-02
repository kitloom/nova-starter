<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Kitloom\AdminMenu\Contracts\MenuOverrides;
use Kitloom\AdminMenu\MenuEditor;
use Kitloom\AdminMenu\MenuOverride;
use Kitloom\Media\Nova\Tools\Media;

/**
 * kitloom/media-nova dropped Media Hub: the «Media» section is now its own
 * tool. Whatever the menu said about the old item (its place, label, parent)
 * goes over to the new one, and items placed under it follow.
 *
 * Only where `arrange_admin_menu_like_wordpress` ran before the change: it
 * writes the new item itself now.
 */
return new class extends Migration
{
    private const MEDIA_HUB = 'Kitloom\Media\Nova\MediaHub\MediaHub';

    public function up(): void
    {
        $this->move(self::MEDIA_HUB, Media::class);
    }

    public function down(): void
    {
        $this->move(Media::class, self::MEDIA_HUB);
    }

    private function move(string $from, string $to): void
    {
        $overrides = app(MenuOverrides::class);
        $all = $overrides->all(MenuOverrides::NOVA);
        $old = $all[$from] ?? null;

        if ($old === null || isset($all[$to])) {
            return;
        }

        // The entry under its new key, and whatever was placed under it follows.
        $all[$to] = (new MenuOverride($to))->with($old->toArray());
        unset($all[$from]);

        foreach ($all as $identity => $override) {
            if ($override->parent === $from) {
                $all[$identity] = $override->with(['parent' => $to]);
            }
        }

        app(MenuEditor::class)->replace(MenuOverrides::NOVA, $all);
    }
};
