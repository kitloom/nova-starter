import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import react from '@vitejs/plugin-react';
import kitloomTheme, { activeTheme, buildsWithVite, ssrDirectory, themeEntry, themeSsrEntry } from './vendor/kitloom/site-inertia/vite/index.js';

// Build of a Vue or React theme: each has its own directory (build-{theme}), the one the
// site gives @vite, with the list of the theme's templates next to the manifest.
export default defineConfig(({ command }) => {
    // `npm run build` names each theme it builds; otherwise — `npm run dev` — the one
    // the site draws, as Appearance → Themes or `php artisan site:theme` set it.
    const theme = process.env.KITLOOM_BUILD_THEME || activeTheme();

    // No theme, or a Blade one: nothing to build or serve. Leaving quietly keeps
    // `composer run dev` going (its processes stop together only on a failure).
    if (!buildsWithVite(theme)) {
        console.info(theme === ''
            ? 'No theme is active: Vite has nothing to serve. Choose one in Appearance → Themes or with `php artisan site:theme <Name>`, then start it again.'
            : `${theme} is a Blade theme: Vite has nothing to serve.`);
        process.exit(0);
    }

    if (command === 'serve') {
        console.info(`Serving the ${theme} theme, the site's active one. After switching themes, start the dev server again.`);
    }

    return {
        plugins: [
            laravel({
                // themes/{theme} or the theme package in vendor.
                input: [themeEntry(theme)],
                buildDirectory: `build-${theme}`,
                // `vite build --ssr`: the theme's resources/ssr.js into bootstrap/ssr/{theme}, for `inertia:start-ssr`.
                ssr: themeSsrEntry(theme) ?? undefined,
                ssrOutputDirectory: ssrDirectory(theme),
                refresh: true,
            }),
            // `@` is the theme's resources/js; the templates list for the server.
            ...kitloomTheme({ theme }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
            // A React theme's components; a Vue theme has none, so the two never meet.
            react({ include: /\.[jt]sx$/ }),
        ],
        resolve: {
            // A theme package in vendor/ may be a symlink (a path repository): its dependencies
            // and vendor/ come from this application, not from where the link points.
            preserveSymlinks: true,
        },
        server: {
            watch: {
                ignored: ['**/storage/framework/views/**'],
            },
        },
    };
});
