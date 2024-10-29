<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedidos extends Model
{
    use HasFactory, softDeletes;



    public function clientes()
    {
        return $this->belongsTo(Cliente::class);
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