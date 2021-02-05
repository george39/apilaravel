<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Helpers\JwtAuth;

class UserController extends Controller
{
    // REGISTRO
    public function register(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json);

        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $name = (!is_null($json) && isset($params->name)) ? $params->name : null;
        $surname = (!is_null($json) && isset($params->surname)) ? $params->surname : null;
        $role = 'ROLE_USER';
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;

        if(!is_null($email) && !is_null($password) && !is_null($name)){
            //Crar el usuario
            $user = new User();
            $user->email = $email;
            $user->name = $name;
            $user->surname = $surname;
            $user->role = $role;

            $pwd = hash('sha256', $password);
            $user->password = $pwd;

            // Comprobar usuario ducplicado
            $isset_user = User::where('email', '=', $email)->first();

            if(!empty($isset_user) == 0){
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => 400,
                    'message' => 'Usuario creado correctamente'
                );
            }else {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'El usuario ya existe'
                );
            }
        }else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Usuario no creado'
            );
        }

        return response()->json($data, 200);
    }


    // LOGIN
    public function login(Request $request){
        $jwtAuth = new JwtAuth();

        //Recibir post
        $json = $request->input('json', null);
        $params = json_decode($json);

        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        $getToken = (!is_null($json) && isset($params->gettoken)) ? $params->gettoken : null;


        // Cifrar password
        $pwd = hash('sha256', $password);

        if(!is_null($email) && !is_null($password) && ($getToken == null || $getToken == 'false')){
            $signup = $jwtAuth->signup($email, $pwd);
            
            
        }elseif($getToken != null){
            $signup = $jwtAuth->signup($email, $pwd, $getToken);
            

        }else {
            $signup = array(
                'status' => 'error',
                'message' => 'Envia tus datos por post'
            );
        }

        return response()->json($signup, 200);
    }

}
