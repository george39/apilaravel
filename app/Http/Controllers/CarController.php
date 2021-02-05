<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Models\Car;

class CarController extends Controller
{
    //
    public function index(Request $request){
        $cars = Car::all()->load('user');  // Para obtener todos los vehiculos
        return response()->json(array(
            'cars' => $cars,
            'status' => 'success'
        ), 200);
    }

    public function show($id){
        $car = Car::find($id)->load('user');
        if(!isset($params->id)){
            echo 'El id no existe';
        }
        return response()->json(array('car' => $car, 'status' => 'success'),200);
    }

    public function store(Request $request){
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            // Recoger datos por post
            $json =$request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);

            // conseguir el usuario
            $user = $jwtAuth->checkToken($hash, true);

            // Validacion
            
            

            $validate = \Validator::make($params_array, [
                'title' => 'required|min:3',
                'description' => 'required',
                'price' => 'required',
                'status' => 'required',
            ]);
            
            if($validate->fails()){
                return response()->json($validate->errors(), 400);
            }
            


            // Guardar el carro
            
            $car = new Car();
            $car->user_id = $user->sub;
            $car->title = $params->title;
            $car->description = $params->description;
            $car->price = $params->price;
            $car->status = $params->status;

            $car->save();

            $data = array(
                'car' => $car,
                'status' => 'success',
                'code' => 200
            );

        } else {
            // Devolver error
            $data = array(
                'message' => 'login incorecto',
                'status' => 'error',
                'code' => 400
            );
        }

        return response()->json($data, 200);
    }

    public function update($id, Request $request){
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            // Recoger parametros post
            $json = $request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);


            // Validacion
            $validate = \Validator::make($params_array, [
                'title' => 'required|min:3',
                'description' => 'required',
                'price' => 'required',
                'status' => 'required',
            ]);
            
            if($validate->fails()){
                return response()->json($validate->errors(), 400);
            }

            // Actualizar el registro
            unset($params_array['id']);
            unset($params_array['user_id']);
            unset($params_array['created_at']);
            unset($params_array['user']);
            
            $car = Car::where('id', $id)->update($params_array);

            $data = array(
                'car' => $params,
                'status' => 'success',
                'code' => 200
            );

        } else { // Devolver error
            $data = array(
                'message' => 'login incorecto',
                'status' => 'error',
                'code' => 400
            );
        }

        return response()->json($data, 200);

    }


    public function destroy($id, Request $request){
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            // Comprobar que existe el registro
            $car = Car::find($id);


            // Borrarlo
            $car->delete();

            // Devolverlo
            $data = array(
                'car' => $car,
                'status' => 'success',
                'code' => 200
            );


        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Login incorrecto'
            );
        }

        return response()->json($data, 200);
    }

}
