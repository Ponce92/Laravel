<?php
/**
 * Created by PhpStorm.
 * User: ponce
 * Date: 08-16-18
 * Time: 09:08 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table='tbl_roles';
    protected $primaryKey='pk_id_rol';
    public $timestamps=false;

}