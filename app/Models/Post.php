<?php

declare(strict_types=1);

namespace App\Models;

use Kitloom\WpSchema\Models\Post as WordPressPost;

/**
 * Site post: schema from kitloom/wp-schema. Modules read their data from the
 * tables themselves; the site's own behaviour would go here.
 */
class Post extends WordPressPost
{
    //
}
