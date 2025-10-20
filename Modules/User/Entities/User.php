<?php

namespace Modules\User\Entities;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Core\Helpers;
use App\Models\Accounts;
use App\Models\Portfolio;
use App\Models\Roles\UserRole;
use App\Models\SharedRoles;
use App\Models\Transactions;
use App\Models\UserPreferences;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Permission\Entities\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasPermission($permission)
    {
        $userId = $this->id;

        return UserRole::query()
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->join('role_permissions', 'roles.id', '=', 'role_permissions.role_id')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('user_roles.user_id', $userId)
            ->where('permissions.code', $permission)
            ->exists();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
    public function preferences()
    {
        return $this->hasOne(UserPreferences::class);
    }
    public function accounts()
    {
        return $this->belongsToMany(Accounts::class, "accounts_user", "user_id", "account_id")->withPivot("shared_role_id");
    }
    public function shared_roles()
    {
        return $this->belongsTo(SharedRoles::class, "shared_role_id");
    }
    public function transactions()
    {
        return $this->hasMany(Transactions::class, "user_id");
    }
    public function portfolios()
    {
        return $this->belongsToMany(Portfolio::class, "user_portfolios", "user_id", "portfolio_id")->withPivot("shared_role_id");
    }
    public function getBalance()
    {
        $balance = Helpers::formatMoneyWithSymbol(Accounts::getUserAccounts($this->id, ['sumBalance'])->sum('balance'));
        return $balance['value'] . ' ' . $balance['unit'];
    }
    public function getCurrency()
    {
        return $this->preferences->getCurrency()->symbol;
    }
    public function getCurrencyCode()
    {
        return $this->preferences->getCurrency()->code;
    }
}
