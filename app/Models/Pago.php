<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Pago extends Model {
    protected $fillable = [
        'pedido_id', 'codigo_operacion', 'monto',
        'metodo', 'estado', 'comprobante_imagen', 'notas', 'fecha_pago'
    ];
 
    protected $casts = ['fecha_pago' => 'datetime'];
 
    public function pedido() {
        return $this->belongsTo(Pedido::class);
    }
 
    public function getEstadoBadgeAttribute() {
        return match($this->estado) {
            'pendiente'    => 'warning',
            'verificando'  => 'info',
            'confirmado'   => 'success',
            'rechazado'    => 'danger',
            default        => 'secondary',
        };
    }
}
 