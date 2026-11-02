<?php

declare(strict_types=1);

namespace App\Nova\Tools;

use App\Nova\User;
use Illuminate\Http\Request;
use Kitloom\Permissions\Nova\RolesTool;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Tool;

/**
 * The "Users" section as in WordPress: all users, a new one, your own
 * profile and roles (kitloom/permissions-nova).
 *
 * A separate tool rather than the resource's menu(): kitloom/admin-menu-nova identifies
 * a resource's section by its URL, and that is the same as the "All Users" item's.
 */
class Users extends Tool
{
    public function menu(Request $request): MenuSection
    {
        $path = '/resources/'.User::uriKey();

        return MenuSection::make(User::label(), [
            MenuItem::make(__('All Users'), $path)
                ->canSee(fn (Request $request): bool => User::authorizedToViewAny($request)),
            MenuItem::make(__('Add New User'), $path.'/new')
                ->canSee(fn (Request $request): bool => User::authorizedToCreate($request)),
            MenuItem::make(__('Profile'), $path.'/'.$request->user()?->getKey().'/edit')
                ->canSee(fn (Request $request): bool => $request->user() !== null
                    && (new User($request->user()))->authorizedToUpdate($request)),
            RolesTool::menuItem(),
        ])
            ->icon('users')
            ->collapsable();
    }
}
