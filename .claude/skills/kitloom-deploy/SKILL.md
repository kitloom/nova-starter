---
name: kitloom-deploy
description: Deploy this kitloom site — to a VPS (DigitalOcean, Hetzner, AWS EC2 or Lightsail), Laravel Cloud, Laravel Forge or Ploi, any Docker host, or DigitalOcean's App Platform — with the site's deploy kit. Use it whenever the owner asks to deploy, host, publish or put the site online, set up a server, make deploys run from CI, or build a Docker image of the site.
---

# Deploying this site

This is a kitloom site: a Laravel application on the WordPress data model,
with a Filament or Nova admin, modules, themes and an MCP server. It deploys
like any Laravel application, plus the four things a CMS adds: uploads that
must outlive a deploy, a queue worker, the scheduler every minute, and
Composer packages from a private repository (`packages.kitloom.dev`, with
the owner's token).

The site carries a deploy kit that writes every file a target needs,
filled in from the site as it is installed — the admin and its path, whether
a theme is built with npm, whether the worker runs Horizon, the database's
prefix. Use it; never write these files by hand:

```bash
php artisan kitloom:deploy-kit                       # the targets
php artisan kitloom:deploy-kit <target> --domain=example.com [--repository=git@github.com:owner/site.git] [--ci=github|gitlab|none]
```

| Target | When | What it writes |
| --- | --- | --- |
| `server` | one VPS on Ubuntu 24.04: a DigitalOcean Droplet, a Hetzner server, AWS EC2 or Lightsail | `deploy/server.sh` (sets the server up, run as root), `deploy/cloud-init.yaml` (the same at first boot, as user data), `deploy.php` (a Deployer recipe: releases, rollback, a smoke check), a GitHub Actions or GitLab workflow |
| `laravel-cloud` | cloud.laravel.com | the checklist with this site's values, `deploy/laravel-cloud.env` to paste |
| `forge` | Laravel Forge or Ploi on any VPS | `deploy/forge.sh` / `deploy/ploi.sh` to paste, `deploy/forge.env`, the checklist |
| `docker` | any Docker host: a VPS with compose, Coolify, AWS ECS or App Runner | `Dockerfile`, `docker/`, `compose.yml`, `deploy/docker.env` |
| `digitalocean-app` | DigitalOcean App Platform | the Docker image and `.do/app.yaml` for `doctl apps create` |

Every target writes `deploy/README.md`: the steps for this site, in order.
Read it after running the kit — it is the plan. Running the kit again
rewrites its own files and keeps the owner's edits.

## Procedure

1. **Ask what you cannot know**, in one go: the target and the provider's
   region; the domain and where its DNS is managed; whether the database
   comes from an existing WordPress site (then its `$table_prefix`); the
   Git host of the repository. Never ask for the kitloom token, a password
   or a key to be pasted into the chat: the files say where each goes
   (`server.sh` asks on the server; `docker/auth.json`; the platform's
   secrets), and the owner puts it there.
2. **Run the kit** for the target, then read `deploy/README.md`.
3. **Do the steps**, with the provider's CLI when it is installed and signed
   in (`doctl`, `hcloud`, `aws`, `gh`), otherwise tell the owner what to
   click. Cloud-init takes a *filled copy* of `deploy/cloud-init.yaml`: make
   it in a temporary folder, pass it as user data, delete it. The copy holds
   the token and must never land in the repository.
   - DigitalOcean: `doctl compute droplet create <name> --region <r> --size s-1vcpu-2gb --image ubuntu-24-04-x64 --ssh-keys <id> --user-data-file <copy>`; App Platform: `doctl databases create` first, then `doctl apps create --spec .do/app.yaml`.
   - Hetzner: `hcloud server create --type cx22 --image ubuntu-24.04 --user-data-from-file <copy>`.
   - AWS: `aws ec2 run-instances --image-id <ubuntu 24.04> --instance-type t3.small --user-data file://<copy>`, or `aws lightsail create-instances --user-data "$(cat <copy>)"`; S3 for the uploads with `MEDIA_DISK=s3`.
4. **The server's deploy key** (the end of `server.sh`'s output, or
   `/var/log/kitloom-server.log` with cloud-init) goes on the repository
   as a read-only deploy key (`gh repo deploy-key add`), before the first
   `vendor/bin/dep deploy`.
5. **Check**, don't assume: the front page and the admin's login page
   answer 200 (`curl -sS -o /dev/null -w '%{http_code}' https://<domain>/up`
   and `/admin/login` or `/nova/login`); `dep deploy` runs its own smoke
   check; **Site Health** in the admin says whether the scheduler and the
   worker come by. Then `vendor/bin/dep kitloom:user` (or the platform's
   console with `php artisan kitloom:user --no-interaction`) for the first
   administrator: the password is shown once — hand it to the owner, keep
   it nowhere.
6. **Commit the kit's files** — never `docker/auth.json`, `deploy/docker.env`
   or a filled `cloud-init.yaml`.

## Rules

- Confirm before anything that costs money or changes DNS — a server, a
  database cluster, a Space, an App Platform app — and name the monthly
  price first.
- Secrets stay out of the chat, the repository and the image. Do not read
  `~/.composer/auth.json` or `.env` files to quote them.
- Change the site and run the kit again rather than editing its files by
  hand; when an edit is unavoidable, tell the owner the kit will keep it.
- Leave `APP_DEBUG=false` and the site's host check as the kit set them.
- The AI module's OAuth sign-in (Laravel Passport) signs every agent's token
  with a key pair: the kit makes it once in a `storage/` that outlives deploys
  (`server`, Forge, Ploi) or carries `PASSPORT_PRIVATE_KEY` and
  `PASSPORT_PUBLIC_KEY` in the platform's secrets (`php artisan
  kitloom:passport-keys` prints them). Never make a new pair on a live site:
  every connected agent is signed out.
- `server.sh` and the kit may run again: what is there is left, what is
  missing is added. A failed `dep deploy` leaves the live release untouched;
  `dep rollback` goes back one.
- The whole picture, with every target by hand too:
  https://kitloom.dev/docs/deployment
