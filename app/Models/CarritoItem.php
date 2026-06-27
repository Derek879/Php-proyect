<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarritoItem extends Model {
    use HasFactory;

    protected $table = 'carrito_items';

    protected $fillable = ['user_id', 'producto_id', 'cantidad'];

    public function producto() {
        return $this->belongsTo(Producto::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}