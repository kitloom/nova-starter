<?php

declare(strict_types=1);

namespace App\Nova;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\MergeValue;
use Illuminate\Support\Facades\Gate;
use Kitloom\Permissions\Nova\Fields\UserRole;
use Kitloom\Permissions\Nova\Filters\RoleFilter;
use Kitloom\WpSchema\Nova\Actions\DeleteUser;
use Kitloom\WpSchema\Nova\Actions\IndexActions;
use Kitloom\WpSchema\Nova\Resources\Concerns\AsksTheGate;
use Kitloom\WpSchema\Nova\Users\UserProfileFields;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Panel;
use Laravel\Nova\ResourceTool;

/**
 * @extends Resource<\App\Models\User>
 */
class User extends Resource
{
    use AsksTheGate;
    use PasswordValidationRules;

    /**
     * Users as in the WordPress admin: the list for whoever may list users, a
     * user — yourself always, others with `edit_users`. Nova's own delete is
     * refused: a user is deleted by the "Delete user" action, which asks what
     * happens to their content, as WordPress does.
     */
    protected static function gateAllows(Request $request, string $ability, ?Model $record = null): bool
    {
        $gate = Gate::forUser($request->user());

        return match ($ability) {
            'viewAny' => $gate->allows('list_users'),
            'create', 'replicate' => $gate->allows('create_users'),
            'view', 'update' => $record !== null ? $gate->allows('edit_user', $record) : $gate->allows('edit_users'),
            'delete', 'forceDelete', 'restore' => false,
            default => true,
        };
    }

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * Users are shown in the sidebar by the {@see Tools\Users} section.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array<int, string>
     */
    public static $search = [
        'ID', 'display_name', 'user_email', 'user_login',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, Field|Panel|ResourceTool|MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make('ID', 'ID')->sortable(),

            Gravatar::make()->maxWidth(50),

            // WordPress's columns: they are what is searched, sorted and checked for uniqueness.
            UserProfileFields::username($request),

            // Edited as «Display name publicly as».
            Text::make('Name', 'display_name')
                ->sortable()
                ->onlyOnIndex(),

            Text::make('Email', 'user_email')
                ->sortable()
                ->rules('required', 'email', 'max:100')
                ->creationRules('unique:users,user_email')
                ->updateRules('unique:users,user_email,{{resourceId}},ID'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules($this->passwordRules())
                ->updateRules($this->optionalPasswordRules()),

            UserProfileFields::currentPassword(),

            ...UserProfileFields::make(),

            UserRole::make(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array<int, Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new RoleFilter,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [
            ...app(IndexActions::class)->for('user', $this),
            new DeleteUser,
        ];
    }
}
