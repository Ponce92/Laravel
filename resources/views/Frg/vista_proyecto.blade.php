<div class="row">
    <div class="col-12">
        <br>
        <div class="row">
            <div class="col-10">Datos Generales</div>
            <div class="col-2 align-middle" style="align-content: center;display: flex;align-items: center">
                @if($proyecto->fk_id_titular == $user->pk_id_usuario)
                    <div class="row align-items-center" >
                    <div class="col">
                        <label for="#" class="editar-seccion">Editar &nbsp;&nbsp;</label>
                    </div>
                </div>
                    <div class="col">
                    <i class="fas fa-toggle-off fa-2x inactivo switch-edt"
                       id="sw_dg"
                        onclick="ActivarForm(this.id)"
                    ></i>
                </div>
                @endif
            </div>
            <hr style="margin-top: 0px;">
        </div>
        <br>
        <form action="{{route('proyecto.investigacion.actualizar')}}" method="post" id="sw_dg_fr" class="switch-edt">
            {{ csrf_field()  }}
            <input type="text" value="{{$proyecto->getId()}}" name="_id" hidden>
            <div class="row">
                <div class="col-3">
                    <div class="row justify-content-center">
                        <div class="r" style="width: 200px;height: 200px;border: 2px solid rgb(210,210,210) ;">
                            <i class="{{$valorIcono}} fa-6x {{$valorColor}}" style="font-size: 150px;margin-top: 20px; margin-left:5px;" ></i>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="row">
                        <div class="col-6">
                            <label for="titulo">Titulo proyecto :</label>
                            <input  type="text"
                                    name="titulo"
                                    id="titulo"
                                    class="form-control {{$errors->has('titulo') ? 'is-invalid':''}} edt mb-3"
                                    value="@if($errors->any()){{$errors->has('titulo') ? old('titulo'):''}}@else{{$proyecto->rt_titulo_proyecto}}@endif"
                                    {{$errors->any() ? '':'disabled'}}
                            >
                            <div class="invalid-feedback">{{$errors->first('titulo')}}</div>
                        </div>
                        <div class="col-3">
                            <label for="titulo">Codigo proyecto :</label>
                            <input  type="text"
                                    name="codigo"
                                    id="codigo"
                                    class="form-control mb-3 {{$errors->has('codigo') ? 'is-invalid':''}} edt mb-3"
                                    value="@if($errors->any()){{$errors->has('codigo') ?  old('codigo'):''}}@else{{$proyecto->pk_id_proyecto_investigacion}}@endif"
                                    {{$errors->any() ? '':'disabled'}}
                            >
                            <div class="invalid-feedback">{{$errors->first('codigo')}}</div>
                        </div>
                        <div class="col-3">
                            <label for="titulo">Acronimo proyecto :</label>
                            <input  type="text"
                                    name="acronimo"
                                    id="acronimo"
                                    class="form-control mb-3 {{$errors->has('acronimo') ? 'is-invalid':''}} edt mb-3"
                                    value="@if($errors->any()){{$errors->has('acronimo') ? old('acronimo'):''}}@else{{$proyecto->rt_acronimo_proyecto}}@endif"
                                    {{$errors->any() ? '':'disabled'}}
                            >
                            <div class="invalid-feedback">{{$errors->first('acronimo')}}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label for="titulo">Titular del proyecto :</label>
                            <input  type="text"
                                    name="titular"
                                    id="titular"
                                    class="form-control mb-3"
                                    value="{{$titular->getCorreo()}}"
                                    {{$errors->any() ? '':'disabled'}}
                            >
                        </div>
                        <div class="col-4">
                            @include('Frg.select_estado_proyecto_edit')
                        </div>
                        <div class="col-4">
                            @include('Frg.select_tipo_proyecto')
                        </div>
                    </div>
                </div>
                <div class="col-1"></div>
            </div>

            <div class="row">

                <div class="col-3"></div>
                <div class="col-9">
                    <hr class="all">
                    <div class="row">
                        <div class="col-8">
                            <div class="row">
                                @include('Frg.select_area_conocimiento_edt')
                            </div>

                        </div>
                        <div class="col col-4">

                                @include('Frg.select_objetivo_socioeconomico')

                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="descripcion">Descripcion Proyecto :</label>
                            <textarea   name="descripcion"
                                        id="descripcion"
                                        cols="50"
                                        rows="3"
                                        {{$errors->any() ? '':'disabled'}}
                                        class="form-control edt  mb-3
                                                        {{$errors->has('descripcion') ? 'is-invalid':''}}"
                                        rows="10">@if($errors->any()){{old('descripcion')}}@else{{$proyecto->getDescripcion()}}@endif</textarea>
                        </div>

                    </div>
                </div>
            </div>

        </form>
    </div>
</div>