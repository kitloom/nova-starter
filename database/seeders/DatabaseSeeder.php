<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Kitloom\Forms\ContactForm7;
use Kitloom\Forms\Form;
use Kitloom\Forms\FormProperties;
use Kitloom\Redirects\Models\Redirection;
use Kitloom\Redirects\Rules\Source;
use Kitloom\Redirects\Rules\Sources;
use Kitloom\WpSchema\Contracts\PostContract;
use Kitloom\WpSchema\Install\WordPressInstaller;

/**
 * A first administrator and what WordPress's installer leaves, then a demo
 * site so the admin has something to show: pages, a post, a menu, a
 * redirect, a 404 log entry and a comment. The demo only on an empty site:
 * after a theme's demo (`kitloom:install --demo`) the theme's front page,
 * pages and menus stay as they are.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'user_login' => 'admin',
            'name' => 'Admin',
            'email' => (string) config('database.seed.admin_email'),
            'password' => (string) config('database.seed.admin_password'),
        ]);

        // What WordPress's installer leaves: WordPress runs on this database too.
        WordPressInstaller::make()->install((string) config('app.url'), (string) config('app.name'));

        if (DB::table('posts')->whereIn('post_type', [PostContract::POST_TYPE_POST, PostContract::POST_TYPE_PAGE])->exists()) {
            return;
        }

        $home = $this->post(PostContract::POST_TYPE_PAGE, 'home', 'Home');
        $about = $this->post(PostContract::POST_TYPE_PAGE, 'about', 'About us');
        $hello = $this->post(PostContract::POST_TYPE_POST, 'hello-world', 'Hello world');

        DB::table('options')->insert([
            ['option_name' => 'page_on_front', 'option_value' => (string) $home->ID, 'autoload' => 'yes'],
        ]);

        $this->menu('main', 'Main menu', [$home, $about, $hello]);

        // A category with a post — empty categories do not make it into the sitemap.
        $newsTerm = DB::table('terms')->insertGetId(['slug' => 'news', 'name' => 'News']);
        $news = DB::table('term_taxonomy')->insertGetId(['term_id' => $newsTerm, 'taxonomy' => 'category', 'description' => '']);
        DB::table('term_relationships')->insert(['object_id' => $hello->ID, 'term_taxonomy_id' => $news]);

        if (class_exists(Form::class)) {
            $this->contactForm();
        }

        // kitloom:install may have left the module out.
        if (class_exists(Redirection::class)) {
            $rule = new Redirection;
            $rule->sources = Sources::encode([new Source('old-about')]);
            $rule->url_to = '/about/';
            $rule->header_code = 301;
            $rule->status = 'active';
            $rule->save();

            DB::table('rank_math_404_logs')->insert(['uri' => 'missing-page', 'accessed' => now(), 'times_accessed' => 3]);
        }

        DB::table('comments')->insert([
            'comment_post_ID' => $hello->ID,
            'comment_author' => 'Reader',
            'comment_author_email' => 'reader@example.test',
            'comment_content' => 'Nice first post.',
            'comment_date' => now(),
            'comment_date_gmt' => now(),
        ]);
    }

    /**
     * A Contact Form 7 form and a page with its shortcode. kitloom/forms gives
     * the form its hash on save; the rest is a new form's template and mail.
     */
    public function contactForm(): void
    {
        $form = $this->post(ContactForm7::FORM_TYPE, 'contact-us', 'Contact us');
        FormProperties::write($form, FormProperties::defaults());

        $page = $this->post(PostContract::POST_TYPE_PAGE, 'contacts', 'Contacts');
        $page->forceFill(['post_content' => (new Form($form->fresh() ?? $form))->shortcode()])->save();
    }

    private function post(string $type, string $slug, string $title): Post
    {
        $post = new Post;
        $post->forceFill([
            'post_type' => $type,
            'post_name' => $slug,
            'post_title' => $title,
            'post_author' => 1,
            'post_date' => now(),
            'post_modified' => now(),
            'post_content' => "<p>{$title}: demo content.</p>",
            'post_excerpt' => '',
            'post_status' => PostContract::POST_STATUS_PUBLISH,
            'comment_status' => 'open',
            'ping_status' => 'closed',
            'post_password' => '',
            'to_ping' => '',
            'pinged' => '',
            'post_content_filtered' => '',
            'post_parent' => 0,
            'guid' => '',
            'menu_order' => 0,
            'post_mime_type' => '',
            'comment_count' => 0,
        ])->save();

        return $post;
    }

    /**
     * WordPress menu: a `nav_menu` term, items are `nav_menu_item` posts with meta.
     *
     * @param  array<int, Post>  $targets
     */
    private function menu(string $slug, string $name, array $targets): void
    {
        $termId = DB::table('terms')->insertGetId(['slug' => $slug, 'name' => $name]);
        $menu = DB::table('term_taxonomy')->insertGetId(['term_id' => $termId, 'taxonomy' => 'nav_menu', 'description' => '']);

        foreach ($targets as $order => $target) {
            $item = $this->post('nav_menu_item', "menu-item-{$target->ID}", '');
            $item->forceFill(['menu_order' => $order + 1])->save();

            DB::table('term_relationships')->insert(['object_id' => $item->ID, 'term_taxonomy_id' => $menu]);

            foreach ([
                '_menu_item_type' => 'post_type',
                '_menu_item_object' => $target->post_type,
                '_menu_item_object_id' => $target->ID,
                '_menu_item_menu_item_parent' => 0,
            ] as $key => $value) {
                DB::table('postmeta')->insert(['post_id' => $item->ID, 'meta_key' => $key, 'meta_value' => (string) $value]);
            }
        }
    }
}
