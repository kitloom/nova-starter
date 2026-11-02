<?php

declare(strict_types=1);

/*
 * Storage::fake() keeps a disk in storage/framework/testing/disks/{disk}_test_{token},
 * the token being paratest's process number (TEST_TOKEN: 1, 2, …) — the same in
 * every run, so two runs at once on one machine would empty and fill each other's
 * disks. tools/check.sh names its run (KITLOOM_TEST_RUN) and the token carries
 * it; a single process gets the run's name alone. The token names nothing else
 * here: Laravel's own parallel runner (LARAVEL_PARALLEL_TESTING), which would put
 * it into database names, cache prefixes and view paths, is not used.
 */

$loader = require __DIR__.'/../vendor/autoload.php';

$run = getenv('KITLOOM_TEST_RUN');

if (is_string($run) && $run !== '') {
    $token = getenv('TEST_TOKEN');
    $_SERVER['TEST_TOKEN'] = is_string($token) && $token !== '' ? "{$token}_{$run}" : $run;
}

return $loader;
