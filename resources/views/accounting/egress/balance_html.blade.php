@extends('layouts.backend.template')

@section('content')
    <div class="title-block">
        <div class="float-left">
            <h3 class="title"> Balance de Comprobante de Egreso </h3>
            <p class="title-description"> Esto será llevado a PDF </p>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">        
                        <div class="card-title-block">
                            <div class="float-left">
                                <h3 class="title"> Detalle del balance </h3>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed table-hover" id="myTable">
                                <thead>
                                <th>Código</th>
                                <th>Nombre Cuenta</th>
                                <th>Saldo Inicial</th>
                                <th>Débitos</th>
                                <th>Créditos</th>
                                <th>Saldo Final</th>
                                </thead>
                                <tbody>
                                @php
                                    $debitTotals = 0;
                                    $creditTotals = 0;
                                    $noRepeatPucs = [];
                                @endphp
                                @foreach($pucs as $key => $puc)
                                    @foreach ($ascendingPucs[$puc] as $index => $ascendingPuc)
                                        @if (!in_array($ascendingPuc, $noRepeatPucs) and !in_array($puc, $noRepeatPucs))
                                            @php
                                                array_push($noRepeatPucs, $ascendingPuc);
                                            @endphp
                                            <tr>
                                                <td>{!! $ascendingPuc !!}</td>
                                                <td>{!! $ascendingDescriptions[$puc][$index] !!}</td>
                                                <td>--</td>
                                                <td>--</td>
                                                <td>--</td>
                                                <td>--</td>           
                                            </tr>
                                        @endif
                                    @endforeach
                                    @php
                                        $debitTotals += $debits[$puc];
                                        $creditTotals += $credits[$puc];
                                        $finalBalance = $credits[$puc] - $debits[$puc] - $initialBalance;
                                        array_push($noRepeatPucs, $puc);
                                    @endphp
                                    <tr>
                                        <td>{!! $puc !!}</td>
                                        <td>{!! $descriptions[$puc] !!}</td>
                                        <td>{!! number_format($initialBalance, 2, ",", ".") !!}</td>
                                        <td>{!! number_format($debits[$puc], 2, ",", ".") !!}</td>
                                        <td>{!! number_format($credits[$puc], 2, ",", ".") !!}</td>
                                        <td>{!! number_format($finalBalance, 2, ",", ".") !!}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="2" class="text-right">Totales</th>
                                    <td>--</td>
                                    <td>{!! number_format($debitTotals, 2, ",", ".") !!}</td>
                                    <td>{!! number_format($creditTotals, 2, ",", ".") !!}</td>
                                    <td>--</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>            
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
