<div class="table-responsive">
    <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
        <thead>
        <th>Tipo Doc.</th>
        <th>Documento</th>
        <th>Nombre Completo</th>
        <th>Fecha Nac.</th>
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
                <td>{!! \Carbon\Carbon::parse($patient->birth_date)->format("d/m/Y") !!}</td>
                <td>{!! $patient->age !!}</td>
                <td>
                    <button type="button" class="btn btn-oval btn-primary btn-sm" onclick="sendInfo({{ $patient->id }}, {{ $patient->eps_id }}, '{{ $patient->full_name }}')">
                        Seleccionar
                    </button>
                </td>
            </tr>
        @endforeach
        @else
            <tr>
                <td colspan="6" align="center">
                    Sin resultados que mostrar
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@if (count($patients) > 0)
<div class="float-right">
    {{ $patients->links() }}
</div>
@endif
