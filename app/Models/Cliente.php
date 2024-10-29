<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cliente extends Model
{
    use HasFactory, softDeletes;



    public function PedidosClientes()
    {
        return $this->hasMany(Pedidos::class);
    }

      /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'restored_at' => 'datetime:Y-m-d',
    ];
}



