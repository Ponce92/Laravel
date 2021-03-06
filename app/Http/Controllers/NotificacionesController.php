<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Models\Notificacion;
use App\User;
use App\Models\ProyectosInvestigacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificacionesController extends Controller
{

     public function index(){
        $user=Auth::user();
        $Notificaciones=Notificacion::where('fk_id_usuario','=',$user->getId())->get();
         return view('gestionNotificaciones')
             ->with('user',$user)
             ->with('notificaciones',$Notificaciones);
     }

     public function aceptarRegistro(Request $request){
        if($request->has('codigo_aceptar')){
            $id=$request->get('codigo_aceptar');
            $ntf=Notificacion::findOrFail($id);

            $ntf->rl_vista=true;
            $ntf->save();

            $ntff =new Notificacion();
            $ntff->pk_id_notificacion=str_random(12);
            $ntff->fk_id_usuario=$ntf->fk_id_usuario_remitente;
            $ntff->rl_vista=false;
            $ntff->rt_tipo_notificacion='RSR';
            $fech=Carbon::now();
            $ntff->rf_fecha_creacion=$fech->format('Y-m-d');
            $ntff->fk_id_usuario_remitente='@riues';
            $ntff->save();


            $reg=User::findOrFail($ntf->fk_id_usuario_remitente);

            if($reg->fk_id_estado == 1){
                return  back()->withinfo('Este registro ya se encuentra activo . . . ');
            }
            $reg->fk_id_estado=1;
            $reg->save();

            return redirect()->route('notificaciones')->withsuccess('El investigador se ha activado satisfactoriamente.');

        }
        return back();
     }

     public function rechazarRegistro(Request $request){

         if($request->has('codigo_rechazar')) {
             $user=Auth::user();
             $id = $request->get('codigo_rechazar');
             $ntf = Notificacion::findOrFail($id);


             $ntf->rl_vista = true;
             $ntf->save();

             $reg = User::findOrFail($ntf->fk_id_usuario_remitente);
             if ($reg->fk_id_estado == 3) {
                 return redirect()->route('notificaciones')->withinfo('Este registro ya fue rechazado.');
             }

             $reg->fk_id_estado = 3;
             $reg->save();

             return redirect()->route('notificaciones')->withsuccess('El registro se ha rechazado, puede cambiar el estado desde la interfaz de registros.');
         }
         return back()->withdanger('No puede realizar esta acción');
     }

     public function eliminarNotifiacion(Request $request){
         if($request->has('codigo_eliminar')){
             $id = $request->get('codigo_eliminar');
             $ntf = Notificacion::findOrFail($id);

             $ntf->delete();

             return redirect()->route('notificaciones')
                 ->withsuccess('Notificación eliminada correctamente !');
         }


        return redirect()->route('notificaciones')->withwarning("Error, Recurso no encontrado.");
     }

     public function marcarLeida(Request $request){
         if($request->has('codigo_leida')){
             $id = $request->get('codigo_leida');
             $ntf = Notificacion::findOrFail($id);

             if($ntf->rl_vista ==true){
                 return back()->withdwarring("Notificación ya marcada como vista");
             }

             $ntf->rl_vista=true;
             $ntf->save();
             return redirect()->route('notificaciones')
                 ->withsuccess('Se ha marcado como vista esta notificación');
         }
         return back()->withdwarring("Error, Recurso no encontrado");

     }

     public function SolicitarAmistad(Request $request){
         $id=$request->get('idU');
         $amigo=User::find($id);
         $user=Auth::user();

         $am=Contacto::where('fk_id_user1','=',$user->getId())
                        ->where('fk_id_user2','=',$amigo->getId())->get();

         $am2=Contacto::where('fk_id_user2','=',$user->getId())
             ->where('fk_id_user1','=',$amigo->getId())->get();
         $ss=count($am)+count($am2);

         if($ss > 0){
             return back()->withinfo('Ya tiene a este usuario como contacto');
         }

         if($amigo){

             $ntff =new Notificacion();
             $ntff->pk_id_notificacion=str_random(12);
             $ntff->fk_id_usuario=$amigo->getId();
             $ntff->rl_vista=false;
             $ntff->rt_tipo_notificacion='SSA';
             $fech=Carbon::now();

             $ntff->rf_fecha_creacion=$fech->format('Y-m-d');
             $ntff->fk_id_usuario_remitente=$user->getId();
             $ntff->save();

             return back()->withsuccess('El usuario recibirá una notificación con su solicitud.');
         }else{
             return redirect('/');
         }
     }

    public function ResponderSolicitudAmistad(Request $request){
        $n=$request->get('idN');

        $user=Auth::user();

        $ntf=Notificacion::find($n);
        $ntf->setVista(true);
        $ntf->save();

        $ntff=new Notificacion();
        $ntff->setTipo('RSA');
        $ntff->setId(str_random(12));
        $ntff->setUsuario($ntf->getRemitente()->getId());
        $ntff->setFecha(Carbon::now());
        $ntff->setRemitente($user->getId());
        $ntff->setVista(false);


        $ntff->save();

        /*Creamos la relacion entre usuarios*/

        $contacto = new Contacto();

        $contacto->setUsuario1($user->getId());
        $contacto->setUsuario2($ntff->getUsuario()->getId());

        $contacto->save();

        return back()->withsuccess('Ha aceptado que el usuario se una a su lista de contactos.');
    }

    public function SolicitudAnexion(Request $request){
         $idProyecto=$request->get('codigo_proyecto');
         $idInvitado=$request->get('codigo_invitado');
         $user=Auth::user();

        $val=DB::table('tbl_usuarios_proyectos')
            ->where('fk_id_participante','=',$idInvitado)
            ->where('fk_id_proyecto_investigacion','=',$idProyecto)
            ->get();
        if(count($val)!=0){
            return back()->withsuccess('Error, este usuario ya pertenece al proyecto');
        }

        $ntf =new Notificacion();

        $ntf->setId(str_random(12));
        $ntf->setUsuario($idInvitado);
        $ntf->setVista(false);
        $ntf->setTipo('SAI');
        $ntf->setFecha(Carbon::now());
        $ntf->setProyecto($idProyecto);
        $ntf->setRemitente($user->getid());

        $ntf->save();

        return back()->withsuccess('Éxito en la operación, este usuario recibirá una notificación con su solicitud.');
    }

    public function ResponderAnexion(Request $request){
         $user = Auth::user();
         $idNot=$request->get('idN24');
         $not=Notificacion::find($idNot);

         /*-------------------------------------------------------------------------------------------------------------
          |     | Creamos la entrada para que aparesca como participante de del proyecto
          |-------------------------------------------------------------------------------------------------------------
         */

         $val=DB::table('tbl_usuarios_proyectos')
             ->where('fk_id_participante','=',$user->getId())
             ->where('fk_id_proyecto_investigacion','=',$not->getProyecto()->getId())
            ->get();
         if(count($val)!=0){
            return back()->withsuccess('Error, este usuario ya pertenece al proyecto');
         }

        DB::table('tbl_usuarios_proyectos')->insert([
            [
                'fk_id_participante'=>$not->getUsuario()->getId(),
                'fk_id_proyecto_investigacion'=>$not->getProyecto()->getId(),
            ]
        ]);

        /*-------------------------------------------------------------------------------------------------------------
         |     | Marcamos la notificacion como vista y creamos la nueva notificacion para informar al otro usuario
         |-------------------------------------------------------------------------------------------------------------
         */
        $not->setVista(true);
        $not->save();

        $ntf= new Notificacion();

        $ntf->setId(str_random(12));
        $ntf->setUsuario($not->getRemitente()->getId());
        $ntf->setVista(false);
        $ntf->setTipo('RAP');
        $ntf->setFecha(Carbon::now());
        $ntf->setRemitente($user->getid());

        $ntf->save();
        return back()->withsuccess('Ha aceptado su solicitud, ahora forma parte de su grupo de investigación.');
    }

    public function SolicitarParticipacionProyecto(Request $request){

         $idProyecto=$request->get('idP');

         $user=Auth::user();
         $proyecto=ProyectosInvestigacion::find($idProyecto);

         /*-------------------------------------------------------------------------------------------------------------
          | | Buscamos si este usuario ya pertenece al proyecto (Por si acaso)
          |-------------------------------------------------------------------------------------------------------------
          */

         $val=DB::table('tbl_usuarios_proyectos')
            ->where('fk_id_participante','=',$user->getId())
            ->where('fk_id_proyecto_investigacion','=',$idProyecto)
            ->get();
            if(count($val)!=0){
                return back()->withsuccess('Ya es participante en este proyecto');
            }

        /*-------------------------------------------------------------------------------------------------------------
          | | Si aun no pertenece entoces creamos la notificación......
          |-------------------------------------------------------------------------------------------------------------
          */
        $ntf =new Notificacion();

        $ntf->setId(str_random(12));
        $ntf->setUsuario($proyecto->getTitular()->getId());
        $ntf->setVista(false);
        $ntf->setTipo('SPP');
        $ntf->setFecha(Carbon::now());
        $ntf->setProyecto($idProyecto);
        $ntf->setRemitente($user->getid());

        $ntf->save();
        return back()->withsuccess('Se notificará al titular de proyecto para que responda a su solicitud.');
     }

     public function ResponderParticipacionProyecto(Request $request){

         $idNot=$request->get('idN25');
         $not=Notificacion::find($idNot);
         $not->setVista(true);
         $not->save();
         $proyecto=$not->getProyecto();
         $user=Auth::user();

         /*-------------------------------------------------------------------------------------------------------------
          | | Buscamos si este usuario ya pertenece al proyecto (Por si acaso)
          |-------------------------------------------------------------------------------------------------------------
          */

         $val=DB::table('tbl_usuarios_proyectos')
             ->where('fk_id_participante','=',$not->getRemitente()->getId())
             ->where('fk_id_proyecto_investigacion','=',$proyecto->getId())
             ->get();
         if(count($val)!=0){
             return back()->withsuccess('Este usuario ya se encuentra como participante del proyecto');
         }
         /*-------------------------------------------------------------------------------------------------------------
            |   | Si no es colaborador entonces lo insertamos como participante
            |-------------------------------------------------------------------------------------------------------------
        */
         DB::table('tbl_usuarios_proyectos')->insert([
             [
                 'fk_id_participante'=>$not->getRemitente()->getId(),
                 'fk_id_proyecto_investigacion'=>$not->getProyecto()->getId(),
             ]
         ]);

         $ntf =new Notificacion();

         $ntf->setId(str_random(12));
         $ntf->setUsuario($not->getRemitente()->getId());
         $ntf->setVista(false);
         $ntf->setTipo('RPP');
         $ntf->setFecha(Carbon::now());
         $ntf->setProyecto($proyecto->getId());
         $ntf->setRemitente($user->getid());

         $ntf->save();

         return back()->withsuccess('Se ha agregado al usuario como participante de su proyecto');
     }

    public function getNotificacionesAjax(Request $request)
    {
        $user=Auth::user();
        $notificaciones=Notificacion::where('fk_id_usuario','=',$user->getId())
                                    ->where('rl_vista','=',false)
                                    ->get();

        $vista= view('Frg.FrgNotificaciones')
        
        ->with('ntfs',$notificaciones)
        ->render();

        return response()->json(array('html'=>$vista));

    }
}
