<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zapato;
use App\Models\Imagen;

class ZapatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $zapatos = Zapato::all();

            //convertimos a un array
            $response = $zapatos->toArray();
            $i = 0;

            foreach($zapatos as $producto){
                $response[$i]["categoria"] = $producto->categoria->toArray();
                $response[$i]["marca"] = $producto->marca->toArray();
                $response[$i]["talla"] = $producto->talla->toArray();  
                //obteniendo las imagenes
                $response[$i]["imagenes"] = $producto->imagenes->toArray();
                $i++;
            }

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
        return view("admin.zapatos");
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
            //$zapato->categoria_id = $request->categoria['id'];
            //$zapato->marca_id = $request->marca['id'];
            //$zapato->talla_id = $request->talla['id'];
            $zapato->categoria_id = $request->categoria_id;
            $zapato->marca_id = $request->marca_id;
            $zapato->talla_id = $request->talla_id;

            $result = $zapato->save();

            //si trae imagenes
            if($request->hasFile('imagenes')){
                foreach($request->file('imagenes') as $imagen){
                    $imageName = time() . '_' . $imagen->getClientOriginalName();
                    $imagen->move(public_path('images/productos/'),$imageName);

                    //creando instancia de la imagen
                    $img = new Imagen();
                    $img->nombre = $imageName;
                    $img->zapato_id= $zapato->id;
                    $img->save();
                }
            }

            if($result > 0){
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
        try {
            $zapato = Zapato::findOrFail($id);

            //convertimos a un array
            $response = $zapato->toArray();
            $response["categoria"] = $zapato->categoria->toArray();
            $response["marca"] = $zapato->marca->toArray();
            $response["talla"] = $zapato->talla->toArray();
            $response["imagenes"] = $zapato->imagenes->toArray();

            return response()->json($response);
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
           /* $nombre = $request->input("nombre");
            $record = Zapato::where("nombre",$nombre)->first();
            if($record){
                return response()->json(["status"=> 'Conflit',"data"=> null,"message"=>'Ya existe unos zapatos con este nombre'],409);
            }*/

            $zapato = Zapato::findOrFail($id);
            $zapato->nombre = $request->nombre;
            $zapato->precio = $request->precio;
            $zapato->estilo = $request->estilo;
            $zapato->descripcion = $request->descripcion;
            $zapato->color = $request->color;
            $zapato->disponible = $request->disponible;
            $zapato->categoria_id = $request->categoria['id'];
            $zapato->marca_id = $request->marca['id'];
            $zapato->talla_id = $request->talla['id'];

            if($zapato->update() > 0){
                return response()->json(["status"=> 'Accepted',"data"=> $zapato,'message'=>'Producto Actualizado...!'],202);
            }else{
                return response()->json(["status"=> 'fail',"data"=> null,'message'=>'Error al actualiza el producto...!'],409);
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
            $zapato = Zapato::findOrFail($id);
            if($zapato->delete()>0){
                return response()->json(["status"=> 'Delete',"data"=> null,'message'=>'Producto Eliminado...!'],205);
            }else{
                return response()->json(["status"=> 'Conflict',"data"=> null,'message'=>'No se puede eliminar este producto...!'],409);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
