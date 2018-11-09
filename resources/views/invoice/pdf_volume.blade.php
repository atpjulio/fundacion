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
@foreach ($invoices as $index => $invoice)
<!--mpdf
<htmlpageheader name="myheader{{ $index }}">
    <table width="100%">
        <tr>
            <td width="15%" style="color:#0000BB; ">
                <img src="/img/logo.png" height="100px">
            </td>
            <td width="40%" >
                <span style="font-weight: bold; font-size: 14pt;">
                    {{ $invoice->company->name }}
                </span>
                <br>
                <strong>
                {{ config('constants.companiesDocumentTypes')[$invoice->company->doc_type] }}:
                </strong> 
                {{ $invoice->company->doc }}
                <br><strong>Dirección:</strong> Carrera 60 No. 46 - 76
                <br><strong>Tel:</strong> 3126214231 - 3157098010
                <br>
            </td>
            <td width="45%" style="text-align: right;">
                <span style="font-weight: bold; font-size: 14pt;">
                    No. Factura
                    <br>
                    {{ $invoice->number }}
                </span>
                <br>
                Resolución de Facturación No.
                <br>
                {{ $invoice->company->billing_resolution }} de 
                {{ \Carbon\Carbon::parse($invoice->company->billing_date)->format("d/m/Y") }}
                <br>
                Desde {{ $invoice->company->billing_start }} hasta el {{ $invoice->company->billing_end }}
            </td>
        </tr>
    </table>
</htmlpageheader>
<htmlpagefooter name="myfooter">
    <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
    Página {PAGENO} de {nb}
    </div>
</htmlpagefooter>
<sethtmlpageheader name="myheader{{ $index }}" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="off" />
mpdf-->
<div style="text-align: right">Fecha de elaboración: {{ date("d/m/Y") }}</div>
    <table width="100%" style="font-family: serif;" cellpadding="10">
        <tr>
            @if ($invoice->multiple)
            <td width="69%" style="border: 0.1mm solid #888888;">
                <span style="font-size: 9pt; color: #555555; font-family: sans;">
                    DATOS DE LA EMPRESA:
                </span>
                <br><br>
                {{ $invoice->eps->code }} - {{ $invoice->eps->name }}
                 - 
                NIT: {{ $invoice->eps->nit }}
                <br>
                Dirección: {{ $invoice->eps->address->address }}
                 - 
                Tel: {{ $invoice->eps->phone->phone }}
            </td>
            <td width="1%">&nbsp;</td>
            <td width="30%" style="border: 0.1mm solid #888888; ">
                <span style="font-size: 9pt; color: #555555; font-family: sans;">
                    SON:
                </span>
                <br><br>
                {{ ucfirst(\App\NumberToLetter::convertirEurosEnLetras(number_format(array_sum(json_decode($invoice->multiple_totals, true)), 0, ",", "."), 0)) }} M/L
            </td>                        

            @else
            <td width="34%" style="border: 0.1mm solid #888888; ">
                <span style="font-size: 9pt; color: #555555; font-family: sans;">
                    DATOS DEL PACIENTE:
                </span>
                <br><br>
                Tipo de Documento: {{ $invoice->authorization->patient->dni_type}}
                <br>
                Número de Documento: {{ $invoice->authorization->patient->dni}}
                <br>
                {{ $invoice->authorization->patient->full_name}}
                <br>                
            </td>
            <td width="1%">&nbsp;</td>
            <td width="34%" style="border: 0.1mm solid #888888;">
                <span style="font-size: 9pt; color: #555555; font-family: sans;">
                    DATOS DE LA EMPRESA:
                </span>
                <br><br>
                {{ $invoice->eps->code }} - {{ $invoice->eps->name }}
                <br>
                NIT: {{ $invoice->eps->nit }}
                <br>
                Dirección: {{ $invoice->eps->address->address }}
                <br>
                Tel: {{ $invoice->eps->phone->phone }}
            </td>
            <td width="1%">&nbsp;</td>
            <td width="30%" style="border: 0.1mm solid #888888; ">
                <span style="font-size: 9pt; color: #555555; font-family: sans;">
                    SON:
                </span>
                <br><br>
                {{ ucfirst(\App\NumberToLetter::convertirEurosEnLetras(number_format($invoice->total, 0, ",", "."), 0)) }} M/L
            </td>                        
            @endif
        </tr>
    </table>
    <br>
    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; border-bottom-width: 0; border-left-width: 0;" cellpadding="8">
        <thead>
        <tr>
            <td width="10%">Codigo</td>
            <td width="35%">Detalle</td>
            <td width="18%">Autorizacion</td>
            <td width="7%">Cant.</td>
            <td width="15%">Valor Unitario</td>
            <td width="15%">Valor Total</td>
        </tr>
        </thead>
        <tbody>
        <!-- ITEMS HERE -->
        @if ($invoice->multiple)
            @php
                $total = 0;
            @endphp
            @foreach (json_decode($invoice->multiple_codes, true) as $k => $authorizationCode)
                @php
                    $a = \App\Authorization::findByCode($authorizationCode);
                    $total += json_decode($invoice->multiple_totals, true)[$k];
                @endphp
                <tr>
                    <td align="center">{{ $a->service->code }}</td>
                    <td>{{ $a->service->name }}</td>
                    <td>{{ $a->codec }}</td>
                    <td align="center">{!! json_decode($invoice->multiple_days, true)[$k] !!}</td>
                    <td class="cost">$ {{ number_format($invoice->eps->daily_price, 0, ",", ".") }}</td>
                    <td class="cost">$ {!! number_format(json_decode($invoice->multiple_totals, true)[$k], 0, ",", ".") !!}</td>
                </tr>            
            @endforeach            
        @else
            @php
                $total = 0;
                $services = $invoice->authorization->eps_service_id;
                $companionService = $invoice->authorization->companion_eps_service_id;

                if ($companionService) {
                    $services .= ",".$companionService;
                }            
            @endphp
            @foreach(explode(",", $services) as $serviceId)    
                @php
                    $service = \App\EpsService::find($serviceId);
                    $total += $invoice->total;
                @endphp
                <tr>
                    <td align="center">{{ $service->code }}</td>
                    <td>{{ $service->name }}</td>
                    <td>{{ $invoice->authorization->codec }}</td>
                    <td align="center">{!! $invoice->days !!}</td>
                    <td class="cost">$ {{ number_format($invoice->eps->daily_price, 0, ",", ".") }}</td>
                    <td class="cost">$ {!! number_format($invoice->total, 0, ",", ".") !!}</td>
                </tr>
            @endforeach
        @endif
        <!-- END ITEMS HERE -->
        <tr style="border-bottom-width: 0;">
            <td class="blanktotal" colspan="4"></td>
            <td class="totals"><b>TOTAL:</b></td>
            <td class="totals cost">
                <b>
                $ {!! number_format($total, 0, ",", ".") !!}
                </b>
            </td>
        </tr>
        </tbody>
    </table>
    <br><br><br>
    <table width="100%" style="font-size: 12pt;" cellpadding="10">
        <tr>
            <td width="45%" style="border-width: 0; line-height: 1.9em;" align="center">
                <br>
                _____________________________________
                <br>
                Firma de la persona autorizada
                <br>
            </td>
            <td width="10%">&nbsp;</td>
            <td width="45%" style="border-width: 0; font-size: 12pt; line-height: 2.1em;">
                Recibido: ___________________________
                <br>
                C.C.: _______________________________
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
        Esta factura de venta se asimila en todos sus efectos legales a una letra de cambio. 
        <br>
        Art. 774 del código de comercio
    </div>
    @if ($index < count($invoices) - 1)
        <pagebreak>
    @endif
@endforeach    
</body>
</html>