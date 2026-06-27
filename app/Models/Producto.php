<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
 
class Producto extends Model {
    use HasFactory;
 
    protected $fillable = [
        'categoria_id', 'nombre', 'slug', 'descripcion',
        'precio', 'stock', 'imagen', 'disponible', 'destacado'
    ];
 
    protected $casts = [
        'disponible' => 'boolean',
        'destacado'  => 'boolean',
        'precio'     => 'decimal:2',
    ];
 
    protected static function boot() {
        parent::boot();
        static::creating(function ($producto) {
            if (empty($producto->slug)) {
                $producto->slug = Str::slug($producto->nombre) . '-' . uniqid();
            }
        });
    }
 
    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }
 
    public function detalles() {
        return $this->hasMany(DetallePedido::class);
    }
 
    public function getImagenUrlAttribute() {
        if ($this->imagen && file_exists(public_path('storage/' . $this->imagen))) {
            return asset('storage/' . $this->imagen);
        }
        return asset('images/default-product.jpg');
    }
}