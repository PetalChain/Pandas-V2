<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Concerns\InteractsWithAuditable;
use App\Enums\AuthLevelEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements FilamentUser, HasTenants, HasDefaultTenant, MustVerifyEmail, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable;
    use InteractsWithAuditable;
    use SoftDeletes;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'organization_id',
        'user_preference_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'auth_level' => AuthLevelEnum::class,
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'managers')
            ->whereNull('managers.deleted_at')
            ->withTimestamps();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function userPreference()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function managedOrganizations()
    {
        return $this->hasMany(Manager::class);

    }

    public function managers()
    {
        return $this->hasMany(Manager::class);
    }

    public function savedProducts()
    {
        return $this->hasMany(SavedProduct::class);
    }

    public function recentViews()
    {
        return $this->hasMany(RecentView::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->trashed()) {
            return false;
        }

        if ($panel->getId() == 'admin') {
            return $this->is_admin;
        }

        return $this->is_admin || $this->is_manager;
    }

    public function getTenants(Panel $panel): array|Collection
    {
        return $this->organizations;
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->organization;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->is_admin) {
            return true;
        }

        return $this->managers()->where('organization_id', $tenant->getKey())->exists();
    }

    protected function isAdmin(): Attribute
    {
        return Attribute::get(fn () => $this->auth_level->value >= AuthLevelEnum::Admin->value)
            ->shouldCache();
    }

    protected function isManager(): Attribute
    {
        return Attribute::get(fn () => $this->loadExists('managers')->managers_exists)
            ->shouldCache();
    }

    protected function isAdminOrManager(): Attribute
    {
        return Attribute::get(fn () => $this->is_admin || $this->is_manager);
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function ($value, $attributes) {
            if ($this->hasMedia('avatar')) {
                return $this->getFirstMediaUrl('avatar');
            }

            $name = str($attributes['name'])
                ->trim()
                ->explode(' ')
                ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
                ->join(' ');

            // $backgroundColor = Rgb::fromString('rgb(' . FilamentColor::getColors()['gray'][950] . ')')->toHex();

            return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=FFFFFF&background=' . str('#111111')->after('#');
        } );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }
}
