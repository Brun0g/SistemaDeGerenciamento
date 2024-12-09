<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedidos extends Model
{
    use HasFactory, softDeletes;



   
    /**

     * The attributes that should be cast.
     *
     * @var array
     
    */

    protected $casts = [
    
        'created_at' => 'datetime:Y-m-d',
        'deleted_at' => 'datetime:Y-m-d',
        'restored_at' => 'datetime:Y-m-d'
        
    ];
}