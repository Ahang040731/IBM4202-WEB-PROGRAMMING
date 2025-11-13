<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Account extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the admin profile associated with the account.
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'account_id');
    }

    /**
     * Get the user profile associated with the account.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'account_id');
    }

    /**
     * Check if the user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }


    /**
     * Ensure this account has a User profile.
     * - If exists, return it.
     * - If missing and role == 'user', auto-create a default profile.
     * - If role is not 'user', return null (let caller handle).
     */
    public function ensureUserProfile(): ?User
    {
        if ($this->role !== 'user') {
            return null; // probably an admin
        }

        if ($this->user) {
            return $this->user;
        }

        // Auto-create a minimal profile to avoid null errors
        $defaultUsername = strstr($this->email, '@', true) ?: 'User'.$this->id;

        return $this->user()->create([
            'username'  => $defaultUsername,
            'phone'     => null,
            'address'   => null,
            'photo'     => null,
            'credit'    => 0,
            'is_active' => true,
        ]);
    }
}