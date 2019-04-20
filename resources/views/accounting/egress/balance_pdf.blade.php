<html>
<head>
    <link rel="icon" href="/favicon.png">
    <style>
        body {font-family: sans-serif;
            font-size: 10pt;
        }
        p { margin: 0pt; }
        table.items {
            border: 0.1mm solid #000000;
        }
        td { vertical-align: top; }
        .items td {
            border-left: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        table thead td { background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
            font-variant: small-caps;
        }
        .items td.blanktotal {
            background-color: #EEEEEE;
            border: 0.1mm solid #000000;
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        .items td.totals {
            text-align: right;
            border: 0.1mm solid #000000;
        }
        .items td.cost {
            text-align: "." center;
        }
    </style>
</head>
<body>
<!--mpdf
<htmlpageheader name="myheader">
    <table width="100%">
        <tr>
            <td width="15%" style="color:#0000BB; ">
                <img src="/img/logo.png" height="100px">
            </td>
            <td width="40%" >
                <span style="font-weight: bold; font-size: 14pt;">
                    {{ $initialEgress->company->name }}
                </span>
                <br>
                <strong>
                {{ config('constants.companiesDocumentTypes')[$initialEgress->company->doc_type] }}:
                </strong> 
                {{ $initialEgress->company->doc }}
                <br><strong>Dirección:</strong> Carrera 60 No. 46 - 76
                <br><strong>Tel:</strong> 3126214231 - 3157098010
                <br>
            </td>
            <td width="45%" style="text-align: right;">
                <span style="font-weight: bold; font-size: 14pt;">
                    Balance de Egresos
                    <br>
                    {{ config('constants.months.'.intval($month)) }} de {{ $year }}
                </span>
            </td>
        </tr>
    </table>
</htmlpageheader>
<htmlpagefooter name="myfooter">
    <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
    Página {PAGENO} de {nb}
    </div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="off" />
mpdf-->
<div style="text-align: right">Fecha de elaboración: {{ \Carbon\Carbon::parse($createdAt)->format("d/m/Y") }}</div>
    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; border-bottom-width: 0; border-left-width: 0;" cellpadding="8">
        <thead>
        <tr>
            <td width="10%">Codigo</td>
            <td width="34%">Nombre Cuenta</td>
            <td width="14%">Saldo Inicial</td>
            <td width="14%">Debitos</td>
            <td width="14%">Creditos</td>
            <td width="14%">Saldo Final</td>
        </tr>
        </thead>
        <tbody>
        @php
            $debitTotals = 0;
            $creditTotals = 0;
            $finalTotals = 0;
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
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>           
                    </tr>
                @endif
            @endforeach
            @php
                $debitTotals += $debits[$puc];
                $creditTotals += $credits[$puc];
                $finalBalance = $initialBalance - $credits[$puc] + $debits[$puc];
                // $finalTotals += $finalBalance;
                array_push($noRepeatPucs, $puc);
            @endphp                                     
            <tr>
                <td>{!! $puc !!}</td>
                <td>{!! $descriptions[$puc] !!}</td>
                <td class="cost">{!! number_format($initialBalance, 2, ",", ".") !!}</td>
                <td class="cost">{!! $debits[$puc] > 0 ? number_format($debits[$puc], 2, ",", ".") : "--" !!}</td>
                <td class="cost">{!! $credits[$puc] > 0 ? number_format($credits[$puc], 2, ",", ".") : "--" !!}</td>
                <td class="cost">{!! number_format($finalBalance, 2, ",", ".") !!}</td>
            </tr>
            @php
                if ($key < count($pucs) - 1) {
                    $initialBalance = $finalBalance;
                }
            @endphp
        @endforeach
        <!-- END ITEMS HERE -->
        <tr style="border-bottom-width: 0;">
            <td class="blanktotal" style="border-right-width: 0;"></td>
            <td class="totals" style="border-bottom-width: 0; border-left-width: 0;">
                <b>TOTAL</b>
            </td>
            <td class="totals cost">
                <b>
                    $ {!! number_format($initialBalance, 0, ",", ".") !!}    
                </b>
            </td>
            <td class="totals cost">
                <b>
                $ {!! number_format($debitTotals, 0, ",", ".") !!}    
                </b>
            </td>
            <td class="totals cost">
                <b>
                $ {!! number_format($creditTotals, 0, ",", ".") !!}
                </b>
            </td>
            <td class="totals cost">
                <b>
                $ {!! number_format($finalBalance, 0, ",", ".") !!}
                </b>
            </td>
        </tr>
        </tbody>
        </tbody>
    </table>
    <br><br><br>
    <table width="100%" style="font-size: 12pt;" cellpadding="10">
        <tr>
            <td width="45%" style="border-width: 0; line-height: 1.9em;" align="center">
                <!--
                <br>
                _____________________________________
                <br>
                Firma de la persona autorizada
                <br>
                -->
            </td>
            <td width="10%">&nbsp;</td>
            <td width="45%" style="border-width: 0; font-size: 12pt; line-height: 2.1em;">
                Recibido: ___________________________
                <br>
                C.C. o NIT: __________________________
                <br>
                Fecha: ________/__________/__________
                <br>            
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;DIA
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MES
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AÑO
            </td>
        </tr>
    </table>
    <br>
    <hr>
    <div style="text-align: center; font-style: italic;">
        Balance de egresos generado automáticamente
    </div>
</body>
</html>