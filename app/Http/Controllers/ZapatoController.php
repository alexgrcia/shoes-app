<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zapato;

class ZapatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $zapatos = Zapato::all();
            return response()->json($zapatos);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $nombre = $request->input("nombre");
            $record = Zapato::where("nombre",$nombre)->first();
            if($record){
                return response()->json(["status"=> 'Conflit',"data"=> null,"message"=>'Ya existe unos zapatos con este nombre'],409);
            }

            $zapato = new Zapato();
            $zapato->nombre = $request->nombre;
            $zapato->precio = $request->precio;
            $zapato->estilo = $request->estilo;
            $zapato->descripcion = $request->descripcion;
            $zapato->color = $request->color;
            $zapato->disponible = $request->disponible;
            $zapato->categoria = $request->categoria;
            $zapato->marca = $request->marca;
            $zapato->talla = $request->talla;

            if($zapato->save() > 0){
                return response()->json(["status"=> 'Created',"data"=> $zapato],201);
            }else{
                return response()->json(["status"=> 'fail',"data"=> null],409);
            }
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
