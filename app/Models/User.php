<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kitloom\Permissions\Concerns\HasRoles;
use Kitloom\WpSchema\Contracts\HasCustomFields;
use Kitloom\WpSchema\Contracts\HasMetaTable;
use Kitloom\WpSchema\Models\Concerns\AuthenticatesAsWordPressUser;
use Kitloom\WpSchema\Models\Concerns\IsWordPressUser;

/**
 * A user is a row of WordPress's `users` table: WordPress and the admin share
 * the accounts, their passwords and their meta.
 */
class User extends Authenticatable implements HasCustomFields, HasMetaTable
{
    /** @use HasFactory<UserFactory> */
    use AuthenticatesAsWordPressUser, HasFactory, HasRoles, IsWordPressUser, Notifiable;
}
