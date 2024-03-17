<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $marcas = Marca::all();
            return response()->json($marcas);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.marcas");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $nombre = $request->input("nombre");
            $record = Marca::where("nombre",$nombre)->first();
            if($record){
                return response()->json(["status"=> 'Conflit',"data"=> null,"message"=>'Ya existe una marca con este nombre'],409);
            }
            
            $marca = new Marca();
            $marca->nombre = $request->nombre;
            if($marca->save() > 0){
                return response()->json(["status"=> 'Created',"data"=> $marca],201);
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
        try {
            $marca = Marca::findOrFail($id);
            return response()->json($marca);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
        try {
            $nombre = $request->input("nombre");
            $record = Marca::where("nombre",$nombre)->first();
            if($record){
                return response()->json(["status"=> 'Conflit',"data"=> null,"message"=>'Ya existe una marca con este nombre'],409);
            }
            $marca = Marca::findOrFail($id);
            $marca->nombre = $request->nombre;
            if($marca->update() > 0){
                return response()->json(["status"=> 'Updated',"data"=> $marca,"message"=>'Marca Actualizada...!'],202);
            }else{
                return response()->json(["status"=> 'fail',"data"=> null,"message"=>"Error al intentar guardar la marca"],409);
            }
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $marca = Marca::findOrFail($id);
            if($marca->delete()>0){
                return response()->json(["status"=> 'Deleted',"data"=> null,"message"=>'Marca Eliminada...!'],205);
            }else{
                return response()->json(["status"=> 'Conflit',"data"=> null,"message"=>'No se puede eliminar esta marca...!'],202);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
