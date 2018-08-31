@extends('layouts.backend.template')

@section('content')
    <div class="title-block">
        <h3 class="title"> Bienvenid@  {!! auth()->user()->full_name !!} </h3>
        <p class="title-description"> Descripción de las opciones que tienes disponible </p>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-xl-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> Autorizaciones </p>
                        </div>
                    </div>
                    <div class="card-block">
                        <p>Podrás ver un listado de las autorizaciones que hay en el sistema y será el primer paso al recibir a un nuevo usuario. Se ingresa la información del usuario y ya quedará asentado en el sistema</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('authorization.index') }}">
                            Ir a Autorizaciones
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> Usuarios </p>
                        </div>
                    </div>
                    <div class="card-block">
                        <p>Verás un listado de los usuarios, aquí vendrás siempre que al momento de registrar una autorización el usuario no se encuentre en el sistema, así con el paso del tiempo todos estarán registrados</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('patient.index') }}">
                            Ir a Usuarios
                        </a>
                    </div>
                </div>
            </div>
            @role('admin')
            <div class="col-xl-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> Facturas </p>
                        </div>
                    </div>
                    <div class="card-block">
                        <p>Verás un listado de las facturas. Esta opción es para la creación, modificación o incluso eliminación de las facturas, cada una de ellas va a atada a una autorización que ya debe de existir</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('invoice.index') }}">
                            Ir a Facturas
                        </a>
                    </div>
                </div>
            </div>
            @endrole
            @role('admin')
            <div class="col-xl-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> EPS </p>
                        </div>
                    </div>
                    <div class="card-block">
                        <p>Verás un listado de las EPS registradas en el sistema, aquí puedes añadir, modificar o eliminar las EPS del sistema. También aquí se define el monto que se cobra por día para cada una</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('eps.index') }}">
                            Ir a EPS
                        </a>
                    </div>
                </div>
            </div>
            @endrole
        </div>
    </section>
@endsection
