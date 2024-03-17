<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Talla;

class TallaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $talla = Talla::all();
            return response()->json($talla);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.tallas");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $nombre = $request->input("talla");
            $record = Talla::where("talla",$nombre)->first();
            if($record){
                return response()->json(["status"=> 'Conflit',"data"=> null,"message"=>'Ya existe una talla con este nombre'],409);
            }

            $talla = new Talla();
            $talla->talla = $request->talla;
            if($talla->save() > 0){
                return response()->json(["status"=> 'Created',"data"=> $talla],201);
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
            $talla = Talla::findOrFail($id);
            return response()->json($talla);
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
            $nombre = $request->input("talla");
            $record = Talla::where("talla",$nombre)->first();
            if($record){
                return response()->json(["status"=> 'Conflit',"data"=> null,"message"=>'Ya existe una talla con este nombre'],409);
            }
            $talla = Talla::findOrFail($id);
            $talla->talla = $request->talla;
            if($talla->update() > 0){
                return response()->json(["status"=> 'Updated',"data"=> $talla,"message"=>'Talla Actualizada...!'],202);
            }else{
                return response()->json(["status"=> 'fail',"data"=> null,"message"=>"Error al intentar guardar la talla"],409);
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
            $talla = Talla::findOrFail($id);
            if($talla->delete()>0){
                return response()->json(["status"=> 'Deleted',"data"=> null,"message"=>'Talla Eliminada...!'],205);
            }else{
                return response()->json(["status"=> 'Conflit',"data"=> null,"message"=>'No se puede eliminar esta talla...!'],202);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
