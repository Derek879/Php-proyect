<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{

    public function index(Request $request)
    {
        $query = Pago::with('pedido');

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        $pagos = $query->orderByDesc('created_at')->paginate(15);

        return view('pagos.index', compact('pagos'));
    }

    public function create(Request $request)
    {
        $pedido_id = $request->pedido;

        $pedido = $pedido_id
            ? Pedido::with('detalles')->findOrFail($pedido_id)
            : null;

        $pedidos = Pedido::whereDoesntHave('pago')
            ->orWhereHas('pago', function ($q) {
                $q->where('estado', 'rechazado');
            })
            ->orderByDesc('created_at')
            ->get();

        return view('pagos.create', compact('pedido', 'pedidos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pedido_id'          => 'required|exists:pedidos,id',
            'codigo_operacion'   => 'nullable|string|max:20',
            'comprobante_imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'notas'              => 'nullable|string|max:500',
        ]);

        $pedido = Pedido::findOrFail($request->pedido_id);

        // Guardar imagen
        $imagenPath = $request->file('comprobante_imagen')
            ->store('comprobantes', 'public');

        // Crear pago
        Pago::create([
            'pedido_id'          => $pedido->id,
            'codigo_operacion'   => $request->codigo_operacion ?? 'SIN-CODIGO',
            'monto'              => $pedido->total,
            'metodo'             => 'yape',
            'estado'             => 'verificando',
            'comprobante_imagen' => $imagenPath,
            'notas'              => $request->notas,
            'fecha_pago'         => now(),
        ]);

        // Actualizar estado del pedido
        $pedido->update([
            'estado' => 'confirmado'
        ]);

        return redirect()
            ->route('pagos.index')
            ->with('success', '¡Pago registrado! Estamos verificando tu Yape 💜');
    }

    public function show(Pago $pago)
    {
        $pago->load('pedido.detalles');

        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        $pago->load('pedido');

        return view('pagos.edit', compact('pago'));
    }

    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            'estado'           => 'required|in:pendiente,verificando,confirmado,rechazado',
            'codigo_operacion' => 'nullable|string|max:20',
            'notas'            => 'nullable|string|max:500',
        ]);

        $pago->update([
            'estado'           => $request->estado,
            'codigo_operacion' => $request->codigo_operacion,
            'notas'            => $request->notas,
        ]);

        if ($request->estado === 'confirmado') {
            $pago->pedido->update([
                'estado' => 'preparando'
            ]);
        }

        return redirect()
            ->route('pagos.index')
            ->with('success', '¡Pago actualizado!');
    }

    public function destroy(Pago $pago)
    {
        if ($pago->comprobante_imagen) {
            Storage::disk('public')->delete($pago->comprobante_imagen);
        }

        $pago->delete();

        return redirect()
            ->route('pagos.index')
            ->with('success', '¡Registro de pago eliminado!');
    }
}