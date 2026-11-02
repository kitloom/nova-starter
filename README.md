# kitloom — Nova skeleton

A Laravel 13 application with kitloom: a CMS that runs on a WordPress
database — its tables, its URLs and its template hierarchy — with the
Laravel Nova 5 admin at `/nova`, the site's themes and the modules you keep.

- Documentation: <https://kitloom.dev/docs/installation.html>
- Packages: `https://packages.kitloom.dev`, with the access from your purchase

## Requirements

PHP 8.4 or newer, Composer, Node.js 20 or later and npm for a Vue or React
theme, and SQLite (the default) or MySQL 8 / MariaDB.
Laravel Nova needs a license of its own: Composer asks for it at
`nova.laravel.com`.

## Install

With the [Laravel installer](https://laravel.com/docs/starter-kits#community-maintained-starter-kits),
and the email and key from your purchase:

```bash
composer config --global http-basic.packages.kitloom.dev <email> <key>
composer config --global repositories.kitloom composer https://packages.kitloom.dev
laravel new my-site --using=kitloom/nova-skeleton --phpunit -v
cd my-site
```

`-v` lets the installer ask its questions, `--phpunit` keeps the tests on PHPUnit.
Or with Composer alone:

```bash
composer create-project kitloom/nova-skeleton my-site --repository=https://packages.kitloom.dev --add-repository
cd my-site
```

The installer asks which themes and modules the site keeps, which theme is
active, whether to add its demo and whether to build the themes; what you
leave out is removed with `composer remove`. Without questions
(`--no-interaction`) the site keeps the usual modules and **no theme**: it
answers with JSON, for a front end of your own.

Set `SEED_ADMIN_EMAIL` and `SEED_ADMIN_PASSWORD` in `.env` (see
`.env.example`), then create the first administrator and start the site:

```bash
php artisan db:seed
composer run dev     # server, queue worker, logs and Vite together
```

The site is at `APP_URL` (`http://localhost:8000`), the admin at `/nova`.

## A theme later

The packages are named `kitloom/<name>-theme`, the theme by its name
(`kitloom/journal-theme` is `Journal`; the catalog:
<https://kitloom.dev/docs/themes/catalog.html>):

```bash
composer require kitloom/journal-theme
npm install
npm run build          # every Vue and React theme installed
php artisan site:theme Journal
```

A Blade theme needs no build. `php artisan site:theme` says which theme is
active; Appearance → Themes in the admin does the same. The site's database
is the one place that says which theme is active: `npm run dev` serves that
theme (start it again after a switch), and `npm run build` builds them all,
so a switch in the admin needs no build.

## Checks

```bash
composer run lint      # Pint: fixes the code style
composer run analyse   # PHPStan with Larastan, level 8, on app/, database/ and routes/
composer run check     # Pint (reporting only), PHPStan and the tests
composer run test
```

`phpunit.xml` runs `tests/Unit` and `tests/Feature`, your own tests; the
kitloom packages carry theirs.
