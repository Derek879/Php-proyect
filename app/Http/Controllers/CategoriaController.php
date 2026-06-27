<?php
 
namespace App\Http\Controllers;
 
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
 
class CategoriaController extends Controller {
 
    public function index() {
        $categorias = Categoria::withCount('productos')->orderBy('nombre')->paginate(10);
        return view('categorias.index', compact('categorias'));
    }
 
    public function create() {
        return view('categorias.create');
    }
 
    public function store(Request $request) {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:categorias',
            'descripcion' => 'nullable|string|max:500',
            'icono'       => 'nullable|string|max:10',
        ]);
 
        Categoria::create([
            'nombre'      => $request->nombre,
            'slug'        => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
            'icono'       => $request->icono ?? '🍰',
            'activo'      => $request->boolean('activo', true),
        ]);
 
        return redirect()->route('categorias.index')->with('success', '¡Categoría creada exitosamente!');
    }
 
    public function edit(Categoria $categoria) {
        return view('categorias.edit', compact('categoria'));
    }
 
    public function update(Request $request, Categoria $categoria) {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string|max:500',
            'icono'       => 'nullable|string|max:10',
        ]);
 
        $categoria->update([
            'nombre'      => $request->nombre,
            'slug'        => Str::slug($request->nombre),
            'descripcion' => $request->descripcion,
            'icono'       => $request->icono ?? '🍰',
            'activo'      => $request->boolean('activo'),
        ]);
 
        return redirect()->route('categorias.index')->with('success', '¡Categoría actualizada!');
    }
 
    public function destroy(Categoria $categoria) {
        if ($categoria->productos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: tiene productos asociados.');
        }
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', '¡Categoría eliminada!');
    }
}