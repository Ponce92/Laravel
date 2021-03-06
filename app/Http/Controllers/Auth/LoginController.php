<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',['only'=>'ShowLoginForm']);

    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $this -> validate(request(),[
            'usuario'=> 'required|email|max:150|min:4',
            'password' => 'required|string|min:6'
        ]);

        if (Auth::attempt(['email'=>$request->get('usuario'),'password'=>$request->get('password')])){

            $user=Auth::User();

            if($user->fk_id_rol==0){
                return redirect()->route('inicioAdministrador');


            }else{
                //Actualizamos la fecha de acceso de usuario
                $fa=Carbon::now();
                $user=Auth::user();
                $fa=$fa->format('Y-m-d');
                $user->rf_ultimo_acceso_usuario=$fa;
                $user->save();

                return redirect()->route('dashboard');
            }

        }

        return back()->withErrors(['ss'=>'Credenciales incorrectas'])
                     ->withInput(request(['usuario']));

    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    public function NombreUsuario(){
        return 'rt_correo_usuario';
    }

}
