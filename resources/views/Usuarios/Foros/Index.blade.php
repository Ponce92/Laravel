@extends('Common.adminLayout')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/comun.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/Tools/jquery.toolbar.css')}}">
@endsection


@section('menu-sup-02')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{route('foros')}}">Foros</a></li>
    <li class="breadcrumb-item active">{{$foro->getRed()->getNombre()}}</li>
@endsection

@section('menuIzq')
    @include('AdminFragment.FrgMenIzq')
@endsection

@section('default')
    <div class="container-fluid area-trabajo" id="area-trabajo">
        <br>
        <div class="row cabeza-seccion">
            <div class="col-8">
                <h2 class="titulo-seccion titulo">Temáticas del Foro </h2>
            </div>
            <div class="col-4">
                <div class="row justify-content-end">
                    <a href="{{route('tematicas.crear',['id'=>$idf])}}">
                        <button  class="btn bttn bttn-red btn-sm">&nbsp;&nbsp; Agregar Temática &nbsp;&nbsp;</button>
                    </a>
                </div>
            </div>
        </div>
        <hr>
        <br>
        <div class="row">
            <div class="col-md-12">
                @include('Common.FlashMsj')
            </div>
        </div>

        <div class="cuerpo-seccion" style="min-height: 400px;">
            <div class="row">
                @if(count($tematicas) > 0 )
                    <table class="table table-sm">
                        <thead>
                        <tr style="background-color: #aa0000;color: #fff;" >
                            <th scope="col">
                                <div class="row justify-content-center">
                                    Usuario
                                </div>
                            </th>
                            <th scope="col">Título</th>
                            <th scope="col"></th>
                            <th scope="col">Estado</th>
                            <th scope="col">Respuestas</th>
                            <th scope="col">Fecha de Creación</th>
                            <th scope="col">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tematicas as $obj)
                            <tr style="font-size: 14px;">
                                <td colspan="1" align="center">
                                    <a href="{{route('getPerfilInvestigador',['id'=>$obj->getCreador()->getId()])}}">
                                        <img src="{{asset('storage/avatar/'.$obj->getCreador()->getFoto())}}"
                                             alt="Nombre de la persona"
                                             title="Nombre :"
                                             class="rounded-circle"
                                             width="70"
                                             height="70"
                                             style="border: 3px solid rgb(165,165,165);">
                                    </a>

                                </td>
                                <td colspan="2" style="vertical-align: middle"> {{$obj->getTitulo()}}</td>
                                <td  style="vertical-align: middle">{{$obj->getEstado()}}</td>
                                <td colspan="1" style="vertical-align: middle">{{count($obj->getRespuestas())}}</td>
                                <td colspan="1" style="vertical-align: middle">{{$obj->getFecha()}}</td>
                                <td colspan="1" align="center" style="vertical-align: middle">
                                    <a href="{{route('tematica.Index',['id'=>$obj->getId()])}}">
                                        <i class="fas fa-eye fa-2x bttn bttn-ver"></i>
                                    </a>
                                    <a href="{{route('eliminar.tema',['id'=>$obj->getId()])}}" onclick="return confirm('¿Deseas Eliminar la Temática?')">
                                        <i class="fas fa-trash-alt fa-2x bttn bttn-del"></i>
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                @else
                    @include('AdminFragment.frg_default')
                @endif
            </div>


        </div>
        <div class="pie-seccion">
            @if(count($tematicas) > 0 )
                <div class="row justify-content-center">
                    {{ $tematicas->links('Frg.link') }}
                </div>

            @endif


        </div>


    </div>
    <br>

@endsection

@section('js')


@endsection
