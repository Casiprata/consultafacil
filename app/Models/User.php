<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $attributes = [
        'role' => 'PACIENTE',
    ];

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_MEDICO = 'MEDICO';
    const ROLE_PACIENTE = 'PACIENTE';

    const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_MEDICO => 'Medico',
        self::ROLE_PACIENTE => 'Paciente',
    ];

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMedico()
    {
        return $this->role === self::ROLE_MEDICO;
    }

    public function isPaciente()
    {
        return $this->role === self::ROLE_PACIENTE;
    }

    public function canUserAccessPanel($role): bool
    {
        return strtolower($this->role) === strtolower($role);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return strtolower($this->role) === strtolower('ADMIN');;
        } else if ($panel->getId() === 'medico') {
            return strtolower($this->role) === strtolower('MEDICO');;
        } else if ($panel->getId() === 'paciente') {
            return strtolower($this->role) === strtolower('PACIENTE');
        }

        return true;
    }

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

    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'user_id');
    }

    public function medico()
    {
        return $this->hasOne(Medico::class, 'user_id');
    }
}
