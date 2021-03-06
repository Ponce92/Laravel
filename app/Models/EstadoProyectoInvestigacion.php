<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoProyectoInvestigacion extends Model
{
    protected $table="tbl_estados_proyectos";

    public $primaryKey='pk_id_estado';
    public $timestamps=false;
    public $incrementing=false;

    public function getId(){
        return $this->pk_id_estado;
    }

    public  function getEstado(){
        return $this->rt_nombre_estado;
    }

    public function getNombre(){
        return $this->rt_nombre_estado;
    }
    public function setNombre($nom){
        $this->rt_nombre_estado=$nom;
    }

    public function getEstadoo(){
        return $this->rl_estado;
    }

    public function setEstadoo($est){
        $this->rl_estado=$est;
    }


}
