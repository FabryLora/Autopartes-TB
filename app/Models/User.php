<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
    protected $guarded = [];

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

    public function listaDePrecio()
    {
        return $this->hasOne(ListaDePrecios::class, 'lista_de_precios_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    // Vendedor tiene muchos clientes
    public function clientes()
    {
        return $this->hasMany(User::class, 'vendedor_id');
    }

    /**
     * Get the sucursales associated with the user.
     */
    public function sucursales()
    {
        return $this->hasMany(SucursalCliente::class, 'user_id');
    }

    /**
     * Get the ofertas associated with the user.
     */
    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'user_id');
    }
}
