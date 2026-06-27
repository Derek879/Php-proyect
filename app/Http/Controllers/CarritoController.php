<?php

namespace App\Http\Controllers;

use App\Models\CarritoItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller {

    public function agregar(Request $request) {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad'    => 'integer|min:1',
        ]);

        $item = CarritoItem::where('user_id', Auth::id())
                    ->where('producto_id', $request->producto_id)
                    ->first();

        if ($item) {
            $item->increment('cantidad', $request->cantidad ?? 1);
        } else {
            CarritoItem::create([
                'user_id'     => Auth::id(),
                'producto_id' => $request->producto_id,
                'cantidad'    => $request->cantidad ?? 1,
            ]);
        }

        return back()->with('success', '¡Producto agregado al carrito!');
    }

    public function quitar($id) {
        CarritoItem::where('user_id', Auth::id())
                   ->where('producto_id', $id)
                   ->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar() {
        CarritoItem::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Carrito vaciado.');
    }

    public function checkout() {
        $items = CarritoItem::with('producto')
                    ->where('user_id', Auth::id())
                    ->get();

        return view('carrito.checkout', compact('items'));
    }

    public function actualizarCantidad(Request $request, $id) {
        $request->validate(['cantidad' => 'required|integer|min:1']);

        CarritoItem::where('user_id', Auth::id())
                   ->where('producto_id', $id)
                   ->update(['cantidad' => $request->cantidad]);

        return back();
    }
}