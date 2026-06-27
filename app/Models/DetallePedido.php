<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class DetallePedido extends Model {
    protected $table = 'detalle_pedidos';
 
    protected $fillable = [
        'pedido_id', 'producto_id', 'producto_nombre',
        'precio_unitario', 'cantidad', 'subtotal'
    ];
 
    public function pedido() {
        return $this->belongsTo(Pedido::class);
    }
 
    public function producto() {
        return $this->belongsTo(Producto::class);
    }
}
 