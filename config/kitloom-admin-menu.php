<?php

declare(strict_types=1);

return [

    /*
    | The WordPress-like menu order is set by the `arrange_admin_menu_like_wordpress` migration.
    | Everything it does not cover — sections of packages installed later — gets
    | sort 0 and sinks to the bottom: sorts written by the menu screen are at least 100.
    | kitloom/admin-menu merges its own defaults for every other key.
    */

    'default_sort' => 0,

];
