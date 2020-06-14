<div class="table-responsive">
    <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
        <thead>
        <th>Tipo Doc.</th>
        <th>Documento</th>
        <th>Nombre Completo</th>
        <th>Edad</th>
        <th>Opciones</th>
        </thead>
        <tbody>
        @if (count($patients) > 0)
        @foreach($patients as $patient)
            <tr>
                <td>{!! $patient->dni_type !!}</td>
                <td>{!! $patient->dni !!}</td>
                <td>{!! $patient->full_name !!}</td>
                <td>{!! $patient->age !!}</td>
                <td>
                    @role('admin')
                    <a href="{{ route('patient.edit', $patient->id) }}" class="btn btn-pill-left btn-info btn-sm">
                        Editar
                    </a>
                    <a href="javascript:showModal('patient/delete/{{ $patient->id }}')" class="btn btn-pill-right btn-danger btn-sm">
                        Borrar
                    </a>
                    @endrole
                    @role('user')
                    <a href="{{ route('patient.edit', $patient->id) }}" class="btn btn-oval btn-info btn-sm">
                        Editar
                    </a>
                    @endrole
                </td>
            </tr>
        @endforeach
        @else
            <tr>
                <td colspan="5" align="center">
                    Sin resultados que mostrar
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@if (count($patients) > 0)
<div class="float-right">
    {{ $patients->appends(['patient' => request()->get('patient')])->links() }}
</div>
@endif
