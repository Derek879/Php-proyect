<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
 
class Categoria extends Model {
    use HasFactory;
 
    protected $fillable = ['nombre', 'slug', 'descripcion', 'icono', 'activo'];
 
    protected static function boot() {
        parent::boot();
        static::creating(function ($categoria) {
            if (empty($categoria->slug)) {
                $categoria->slug = Str::slug($categoria->nombre);
            }
        });
    }
 
    public function productos() {
        return $this->hasMany(Producto::class);
    }
 
    public function productosActivos() {
        return $this->hasMany(Producto::class)->where('disponible', true);
    }
}
 