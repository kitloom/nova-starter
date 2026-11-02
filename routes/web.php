<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Kitloom\Site\Http\CachePage;
use Kitloom\Site\Http\SiteController;

// Every site address: a page of the active theme, or its data (Accept: application/json).
// Guests get pages from the cache while SITE_CACHE_TTL is set.
Route::fallback(SiteController::class)->middleware(CachePage::class);
