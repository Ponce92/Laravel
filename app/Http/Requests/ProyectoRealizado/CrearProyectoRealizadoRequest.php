<?php

namespace App\Http\Requests\ProyectoRealizado;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CrearProyectoRealizadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
                'nombre'=>['string',
                    Rule::unique('tbl_proyectos_realizados','rt_titulo_proyecto')
                        ->where(function($query){
                            return $query->where('fk_id_usuario',$this->user()->pk_id_usuario);
                        }),
                ],

                'fechaI'=>'date|required',
                'fechaF'=>'date|required',
                'area'=>'numeric|required',
                'area-c'=>'string|required_if:area,"Otra Área de conocimiento"',
                'descripcion'=>'required|string|min:6|max:150'


            ];
    }

    public  function messages()
    {
        return [
            'nombre.unique'=>'Ya posee un proyecto con ese nombre',
            'nombre.string'=>'El campo debe ser una cadena de caracteres y no puede estar vacío',
            'area-c.string'=>'Este campo es obligatorio',

            'fechaI.required'=>'Debe especificar una fecha de inicio del proyecto',
            'fechaI.date'=>'El formato de fecha no es valido',
            'fechaF.required'=>'Debe especificar una fecha de fin del proyecto',
            'fechaF.date'=>'El formato de fecha no es válido',
            'descripcion.string'=>'El campo es obligatorio'

        ];
    }
}
