<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'password_changed_at',
        'temporary_password',
        'password_reset_required',
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

    /**
     * Helper function to check if the user has the 'admin' role.
     * This makes our code in controllers and views cleaner.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user needs to reset their password.
     *
     * @return bool
     */
    public function needsPasswordReset(): bool
    {
        return $this->password_reset_required;
    }

    /**
     * Mark the password as changed.
     *
     * @return void
     */
    public function markPasswordAsChanged(): void
    {
        $this->update([
            'password_reset_required' => false,
            'password_changed_at' => now(),
            'temporary_password' => null,
        ]);
    }

    /**
     * Generate a temporary password.
     *
     * @return string
     */
    public static function generateTemporaryPassword(): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        $password = '';
        $maxIndex = strlen($characters) - 1;
        
        for ($i = 0; $i < 12; $i++) {
            $password .= $characters[random_int(0, $maxIndex)];
        }
        
        return $password;
    }
}
