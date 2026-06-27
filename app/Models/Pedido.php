<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model {
    use HasFactory;

    protected $fillable = [
        'codigo', 'cliente_nombre', 'cliente_telefono', 'cliente_email',
        'direccion_entrega', 'subtotal', 'delivery', 'total',
        'estado', 'notas', 'fecha_entrega'
    ];

    protected $casts = ['fecha_entrega' => 'datetime'];

    const ESTADOS = ['recibido', 'preparando', 'en_camino', 'entregado', 'cancelado'];

    const FLUJO = [
        'recibido'   => ['siguiente' => 'preparando', 'anterior' => null],
        'preparando' => ['siguiente' => 'en_camino',  'anterior' => 'recibido'],
        'en_camino'  => ['siguiente' => 'entregado',  'anterior' => 'preparando'],
        'entregado'  => ['siguiente' => null,          'anterior' => 'en_camino'],
        'cancelado'  => ['siguiente' => null,          'anterior' => null],
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($pedido) {
            if (empty($pedido->codigo)) {
                $pedido->codigo = 'PED-' . strtoupper(uniqid());
            }
            if (empty($pedido->estado)) {
                $pedido->estado = 'recibido';
            }
        });
    }

    public function detalles() {
        return $this->hasMany(DetallePedido::class);
    }

    public function pago() {
        return $this->hasOne(Pago::class);
    }

    public function getEstadoBadgeAttribute(): string {
        return match($this->estado) {
            'recibido'   => 'info',
            'preparando' => 'warning',
            'en_camino'  => 'primary',
            'entregado'  => 'success',
            'cancelado'  => 'danger',
            default      => 'secondary',
        };
    }

    public function getEstadoLabelAttribute(): string {
        return match($this->estado) {
            'recibido'   => '📥 Recibido',
            'preparando' => '👨‍🍳 Preparando',
            'en_camino'  => '🛵 En camino',
            'entregado'  => '✅ Entregado',
            'cancelado'  => '❌ Cancelado',
            default      => ucfirst($this->estado),
        };
    }

    public function getEstadoPasoAttribute(): int {
        return match($this->estado) {
            'recibido'   => 1,
            'preparando' => 2,
            'en_camino'  => 3,
            'entregado'  => 4,
            'cancelado'  => 0,
            default      => 1,
        };
    }

    public function getSiguienteEstadoAttribute(): ?string {
        return self::FLUJO[$this->estado]['siguiente'] ?? null;
    }

    public function getEsActivoAttribute(): bool {
        return !in_array($this->estado, ['entregado', 'cancelado']);
    }
}