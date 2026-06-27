<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller {

    public function index(Request $request) {
        $query = Producto::with('categoria');

        if ($request->categoria_id) {
            $query->where('categoria_id', $request->categoria_id);
        }
        if ($request->buscar) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        $productos  = $query->orderBy('nombre')->paginate(12);
        $categorias = Categoria::all();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create() {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request) {
        $request->validate([
            'nombre'       => 'required|string|max:150',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion'  => 'nullable|string|max:1000',
            'precio'       => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['nombre', 'categoria_id', 'descripcion', 'precio', 'stock']);
        $data['slug']       = Str::slug($request->nombre) . '-' . uniqid();
        $data['disponible'] = $request->boolean('disponible', true);
        $data['destacado']  = $request->boolean('destacado');

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->storeAs(
                'productos',
                uniqid() . '.' . $request->file('imagen')->getClientOriginalExtension(),
                'public'
            );
        }

        Producto::create($data);

        return redirect()->route('productos.index')->with('success', '¡Producto creado exitosamente!');
    }

    public function show(Producto $producto) {
        $producto->load('categoria');
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto) {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto) {
        $request->validate([
            'nombre'       => 'required|string|max:150',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion'  => 'nullable|string|max:1000',
            'precio'       => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['nombre', 'categoria_id', 'descripcion', 'precio', 'stock']);
        $data['disponible'] = $request->boolean('disponible');
        $data['destacado']  = $request->boolean('destacado');

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->storeAs(
                'productos',
                uniqid() . '.' . $request->file('imagen')->getClientOriginalExtension(),
                'public'
            );
        }

        $producto->update($data);

        return redirect()->route('productos.index')->with('success', '¡Producto actualizado!');
    }

    public function destroy(Producto $producto) {
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        $producto->delete();
        return redirect()->route('productos.index')->with('success', '¡Producto eliminado!');
    }
}