<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = Venta::with('cliente', 'productos')->get();
        return response()->json($ventas, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'productos' => 'required|array',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        // Crear la venta
        $venta = Venta::create(['cliente_id' => $request->cliente_id]);

        // Adjuntar productos a la venta
        foreach ($request->productos as $producto) {
            $venta->productos()->attach($producto['producto_id'], [
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario']
            ]);
        }

        // Retornar una respuesta exitosa
        return response()->json($venta, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    // public function show(Venta $venta)
    // {
    //     return response()->json($venta, 200);
    // }
    public function show($id)
    {
        try {
            $venta = Venta::with('cliente', 'productos')->findOrFail($id);
            return response()->json($venta, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'productos' => 'required|array',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $venta->update(['cliente_id' => $request->cliente_id]);

        $productosNuevos = collect($request->productos);

        $venta->productos()->detach();
        foreach ($productosNuevos as $producto) {
            $venta->productos()->attach($producto['producto_id'], [
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario']
            ]);
        }

        return response()->json($venta, 200);
    }

    
    // public function destroy(Venta $venta)
    // {
    //     $venta->productos()->detach();
    //     $venta->delete();
    //     return response()->json(null, 204);
    // }
}
