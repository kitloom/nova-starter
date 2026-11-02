# A kitloom site — Nova edition

Rules for AI assistants (Claude, Copilot, Cursor, …) working in this site.

This is a Laravel application with kitloom, a Laravel CMS. It began as a
way to move WordPress sites onto Laravel and keeps WordPress's data model —
its tables, options and addresses — so such a site comes over as it is; it
is a product of its own now, and new modules are built the Laravel way:
their own tables, models, migrations, events and jobs. The public site is
drawn by a theme, the Laravel Nova admin is at `/nova`. The packages under
`vendor/kitloom/` come with Composer: read them, never edit them, an update
overwrites them. The guide is <https://kitloom.dev/docs>; what a module does
and how it is set up is its README, `vendor/kitloom/<name>/README.md` (the
admin shows it as the module's *How it works*).

## PHP

PHP 8.4 or newer. `php -v` says which one runs; when the one on `PATH` is
older, call the newer one by its path for every command here, Composer
included (`<php> $(which composer) require …`). With Homebrew it is
`$(brew --prefix php@8.4)/bin/php` — under `/opt/homebrew` on Apple Silicon,
`/usr/local` on Intel; Laravel Herd and Docker images bring their own.

## Where code goes

- `app/` — what this site does on top of the packages:
  `NovaServiceProvider`, the user resource, dashboards, the site's own
  routes and jobs.
- The order of the Nova sidebar is the site's data: change it with a
  migration in `database/migrations` (see `arrange_admin_menu_like_wordpress`),
  never by editing a package's menu items.
- A module of the site's own: `php artisan kitloom:make-module Reviews`
  (`--post-type=review` for records that are posts). It writes
  `packages/reviews` and its admins, adds the path repository `packages/*`,
  requires what this site runs and prints the `composer update` to run. The
  packages take the vendor of the site's `name` in `composer.json`
  (`acme/shop` → `acme/reviews`); name the site there, or pass `--vendor`,
  while it is still `kitloom/…`.
  See <https://kitloom.dev/docs/modules/first-module>.
- A theme: `php artisan kitloom:make-theme Recipes`, or a child of an
  installed one with `--parent=Journal`. See
  <https://kitloom.dev/docs/themes/generator>.
- A package behaves differently through its config (`php artisan
  vendor:publish --tag=kitloom-<name>-config`), its events and the
  container tags it reads — <https://kitloom.dev/docs/modules/extending> —
  not by copying its classes.
- WordPress records are written through kitloom's writers, with the user
  who acts: `Kitloom\WpSchema\Content\PostWriter`, `TermWriter`,
  `CommentWriter`, and options through `Kitloom\Settings\SettingWriter`.
  They make slugs unique, fill the dates, check permissions and tell the
  site's listeners; a raw insert does none of it.
- Every PHP file starts with `declare(strict_types=1);`.

## Modules and themes

- A kitloom module: `composer require kitloom/<name> kitloom/<name>-nova`,
  then `php artisan migrate`. The Modules screen switches modules on and off.
- A theme: `composer require kitloom/<name>-theme`, then `npm install && npm
  run build` (every Vue and React theme of the site), then `php artisan
  site:theme <Name>` or Appearance → Themes. A Blade theme needs no build.
- The active theme is the site's `kitloom_theme` option, never a line of
  `.env`: `php artisan site:theme` lists the themes and which is active,
  `--none` leaves the site without one (pages as JSON). `npm run dev` serves
  the active theme — start it again after a switch.

## Checks

```bash
composer run lint      # Pint: fixes the code style
composer run analyse   # PHPStan with Larastan, level 8
composer run check     # Pint (reporting only), PHPStan and the tests
php artisan test
```

`phpunit.xml` runs `tests/Unit` and `tests/Feature`, the site's own tests;
the kitloom packages carry theirs and are tested before each release. A
test of a page or a record extends `Kitloom\WpSchema\Testing\ApplicationTestCase`
and takes its helpers from the packages — see
<https://kitloom.dev/docs/modules/testing>. A Nova form test posts data the
way the browser does (Flexible as JSON plus
`___nova_flexible_content_fields`); a JSON request body breaks whitecube's
request scoping.
