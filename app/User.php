<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Translation\Tests\Writer\BackupDumper;

class User extends Authenticatable
{
    use Notifiable;
    protected $table='tbl_usuarios';
    protected $primaryKey='pk_id_usuario';

    public $timestamps=false;
    public $incrementing=false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'nombre_usuario','clave_usuario',
        //'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

}
