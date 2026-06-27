<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\DetallePedido;
use App\Models\CarritoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller {

    public function index(Request $request) {
        $query = Pedido::with('pago');

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }
        if ($request->buscar) {
            $query->where(function ($q) use ($request) {
                $q->where('codigo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('cliente_nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('cliente_telefono', 'like', '%' . $request->buscar . '%');
            });
        }

        $pedidos = $query->orderByDesc('created_at')->paginate(15);
        return view('pedidos.index', compact('pedidos'));
    }

    public function create() {
        $productos = Producto::with('categoria')
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        // Si el usuario tiene cosas en el carrito, las pre-cargamos en el formulario
        $carritoItems = collect();
        if (Auth::check()) {
            $carritoItems = CarritoItem::with('producto')
                ->where('user_id', Auth::id())
                ->get()
                ->map(function ($item) {
                    return [
                        'producto_id' => $item->producto_id,
                        'nombre'      => $item->producto->nombre,
                        'precio'      => $item->producto->precio,
                        'cantidad'    => $item->cantidad,
                    ];
                })
                ->values();
        }

        return view('pedidos.create', compact('productos', 'carritoItems'));
    }

    public function store(Request $request) {
        $request->validate([
            'cliente_nombre'    => 'required|string|max:150',
            'cliente_telefono'  => 'required|string|max:20',
            'cliente_email'     => 'nullable|email|max:150',
            'direccion_entrega' => 'nullable|string|max:500',
            'delivery'          => 'required|numeric|min:0',
            'notas'             => 'nullable|string|max:500',
            'fecha_entrega'     => 'nullable|date|after:now',
            'productos'         => 'required|array|min:1',
            'productos.*'       => 'required|exists:productos,id',
            'cantidades'        => 'required|array',
            'cantidades.*'      => 'required|integer|min:1',
        ]);

        $subtotal = 0;
        $items    = [];

        foreach ($request->productos as $i => $pid) {
            $prod     = Producto::findOrFail($pid);
            $cant     = $request->cantidades[$i];
            $sub      = $prod->precio * $cant;
            $subtotal += $sub;
            $items[]  = [
                'producto_id'     => $prod->id,
                'producto_nombre' => $prod->nombre,
                'precio_unitario' => $prod->precio,
                'cantidad'        => $cant,
                'subtotal'        => $sub,
            ];
        }

        $total = $subtotal + $request->delivery;

        $pedido = Pedido::create([
            'cliente_nombre'    => $request->cliente_nombre,
            'cliente_telefono'  => $request->cliente_telefono,
            'cliente_email'     => $request->cliente_email,
            'direccion_entrega' => $request->direccion_entrega,
            'subtotal'          => $subtotal,
            'delivery'          => $request->delivery,
            'total'             => $total,
            'notas'             => $request->notas,
            'fecha_entrega'     => $request->fecha_entrega,
            'estado'            => 'recibido',
        ]);

        foreach ($items as $item) {
            $pedido->detalles()->create($item);
        }

        // Ya se convirtió en pedido -> vaciamos el carrito del usuario
        if (Auth::check()) {
            CarritoItem::where('user_id', Auth::id())->delete();
        }

        return redirect()->route('pagos.create', ['pedido' => $pedido->id])
            ->with('success', '¡Pedido creado! Ahora registra el pago por Yape.');
    }

    public function show(Pedido $pedido) {
        $pedido->load(['detalles.producto', 'pago']);
        return view('pedidos.show', compact('pedido'));
    }

    public function edit(Pedido $pedido) {
        return view('pedidos.edit', compact('pedido'));
    }

    public function avanzarEstado(Pedido $pedido) {
        if (!$pedido->es_activo) {
            return back()->with('error', 'Este pedido ya no puede cambiar de estado.');
        }

        $siguiente = $pedido->siguiente_estado;

        if (!$siguiente) {
            return back()->with('error', 'El pedido ya está en su estado final.');
        }

        $pedido->update(['estado' => $siguiente]);

        $labels = [
            'preparando' => 'El pedido está siendo preparado 👨‍🍳',
            'en_camino'  => 'El pedido salió con el repartidor 🛵',
            'entregado'  => '¡Pedido entregado exitosamente! ✅',
        ];

        return back()->with('success', $labels[$siguiente] ?? 'Estado actualizado.');
    }

    public function cancelarPedido(Pedido $pedido) {
        if (!$pedido->es_activo) {
            return back()->with('error', 'Este pedido ya no se puede cancelar.');
        }

        $pedido->update(['estado' => 'cancelado']);
        return back()->with('success', 'Pedido cancelado.');
    }

    public function updateEstado(Request $request, Pedido $pedido) {
        $request->validate([
            'estado' => 'required|in:' . implode(',', Pedido::ESTADOS),
        ]);

        $pedido->update(['estado' => $request->estado]);
        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function update(Request $request, Pedido $pedido) {
        $request->validate([
            'estado'            => 'required|in:' . implode(',', Pedido::ESTADOS),
            'cliente_nombre'    => 'required|string|max:150',
            'cliente_telefono'  => 'required|string|max:20',
            'cliente_email'     => 'nullable|email',
            'direccion_entrega' => 'nullable|string|max:500',
            'notas'             => 'nullable|string|max:500',
            'fecha_entrega'     => 'nullable|date',
            'delivery'          => 'required|numeric|min:0',
        ]);

        $subtotal = $pedido->detalles->sum('subtotal');
        $total    = $subtotal + $request->delivery;

        $pedido->update([
            'estado'            => $request->estado,
            'cliente_nombre'    => $request->cliente_nombre,
            'cliente_telefono'  => $request->cliente_telefono,
            'cliente_email'     => $request->cliente_email,
            'direccion_entrega' => $request->direccion_entrega,
            'notas'             => $request->notas,
            'fecha_entrega'     => $request->fecha_entrega,
            'delivery'          => $request->delivery,
            'total'             => $total,
        ]);

        return redirect()->route('pedidos.show', $pedido)->with('success', '¡Pedido actualizado!');
    }

    public function destroy(Pedido $pedido) {
        $pedido->delete();
        return redirect()->route('pedidos.index')->with('success', '¡Pedido eliminado!');
    }
}