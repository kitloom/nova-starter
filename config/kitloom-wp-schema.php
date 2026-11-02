<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\TermTaxonomy;

return [

    /*
    | The site's own model classes. The rest are kitloom/wp-schema models.
    */

    /*
    | Site sidebars — tabs on the widgets page and `widgets` keys in the payload.
    */

    'sidebars' => ['sidebar', 'footer'],

    'models' => [
        'post' => Post::class,
        'term_taxonomy' => TermTaxonomy::class,
    ],

];
