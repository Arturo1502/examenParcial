<?php

namespace App\Http\Controllers;


use App\Models\Proveedore;
use Illuminate\Http\Request;

class ProveedoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proveedore = Proveedore::all();
        return response()->json($proveedore, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:proveedores,email',
            'compañia' => 'required|string',
        ]);

        $proveedore = Proveedore::create($request->all());
        return response()->json($proveedore, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proveedore  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedore $proveedore)
    {
        return response()->json($proveedore, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proveedore  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proveedore $proveedore)
    {
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:proveedores,email,' . $proveedore->id,
            'compañia' => 'required|string',
        ]);

        $proveedore->update($request->all());
        return response()->json($proveedore, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proveedore  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedore $proveedore)
    {
        $proveedore->delete();
        return response()->json(null, 204);
    }
}
