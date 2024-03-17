<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Detalle_Reserva;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
            $reservas = Reserva::all();
            $response = $reservas->toArray();
            $i=0;
            foreach($reservas as $reserva){
                $response[$i]["cliente"] =  $reserva->usuario->toArray();
                //obtiene el detalle de la reserva
                $detalle = $reserva->detalleReservas->toArray();
                
                //recorriendo el deltalle de la reserva
                $f  =0;
                $reserva["monto"] = 0;
                foreach($reserva->detalleReservas as $item){
                    $detalle[$f]['zapato'] = $item->zapato->toArray();
                    $detalle[$f]['zapato']['marca'] = $item->zapato->marca->toArray();
                    $detalle[$f]['zapato']['categoria'] = $item->zapato->categoria->toArray();
                    $detalle[$f]['zapato']['talla'] = $item->zapato->talla->toArray();
                    $reserva["monto"] = $reserva["monto"]  + ($detalle[$f]['cantidad'] * $detalle[$f]['zapato']['precio']) ;
                    $f++;

                }
                $response[$i]['detalleReservas']=$detalle;
                $i++;
             }
            return response()->json($reservas);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.reservas");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $errors = 0;
            DB::beginTransaction();
            //creamos la instancia de la reserva
            $reserva = new Reserva();
            $reserva->fecha = $request->fecha;
            $reserva->estado = $request->estado;
            //$reserva->usuario_id = $request->usuario_id;
            $reserva->usuario_id = $request->user['id'];

            if($reserva->save() < 0){
                $errors++;
            }

            //obtener el detalle de la orden
            $detalle = $request->detalleReserva;
            foreach($detalle as $key => $det){
                //creamos el objeto DetalleReserva
                $detalleReserva = new Detalle_Reserva();
                $detalleReserva->cantidad = $det['cantidad'];
                $detalleReserva->descripcion = $det['descripcion'];
                $detalleReserva->zapato_id = $det['zapato']['id'];
                $detalleReserva->reserva_id = $reserva->id;

                if($detalleReserva->save()<0){
                    $errors++;
                }
            }

            if($errors == 0){
                DB::commit();
                return response()->json(["status"=> 'Created',"data"=> $reserva,"message"=>'Se reserva ha sido registada!'],201);
            }else{
                DB::rollBack();
                return response()->json(["status"=> 'Not Acceptable',"data"=> null,"message"=>'Error al guardar la reserva, intente de nuevo!'],406);
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
            
            $reserva = Reserva::find($id);
            $response = $reserva->toArray();
            $response["cliente"] =  $reserva->usuario->toArray();

            //obtiene el detalle de la reserva
            $detalle = $reserva->detalleReservas->toArray();

            $i=0;

                foreach($reserva->detalleReservas as $item){
                    $detalle[$i]['zapato'] = $item->zapato->toArray();
                    $detalle[$i]['zapato']['marca'] = $item->zapato->marca->toArray();
                    $detalle[$i]['zapato']['categoria'] = $item->zapato->categoria->toArray();
                    $detalle[$i]['zapato']['talla'] = $item->zapato->talla->toArray();
                    $i++;

                }
                $response['detalleReservas']=$detalle;
            return response()->json($reserva);
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
            $reserva = Reserva::find($request->id);
            if($request->estado == 'D'){
                $fechaActual = date('Y-m-d');
            }
            $reserva->estado = $request->estado;

            if($reserva->update()>0){
                return response()->json(["status"=> 'Accepted',"data"=> $reserva,"message"=>'El estado de la orden ha sido cambiada!'],202);
            }else{
                return response()->json(["status"=> 'Created',"data"=> null,"message"=>'Error al cambiar el estado de la orden'],201);
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
        //
    }

    public function changeState(Request $request){
        try {
            $reserva = Reserva::find($request->id);
            if($request->estado == 'D'){
                $fechaActual = date('Y-m-d');
            }
            $reserva->estado = $request->estado;

            if($reserva->update()>0){
                return response()->json(["status"=> 'Accepted',"data"=> $reserva,"message"=>'El estado de la orden ha sido cambiada!'],202);
            }else{
                return response()->json(["status"=> 'Created',"data"=> null,"message"=>'Error al cambiar el estado de la orden'],201);
            }


        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
