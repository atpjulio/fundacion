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
                <img src="{{ $invoice->company->logo }}" height="100px">
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
                <br><strong>Dirección:</strong> {{ $invoice->company->address->full_address }}
                <br><strong>Tel:</strong> {{ $invoice->company->phone->full_phone }}
                <br>
            </td>
            <td width="45%" style="text-align: right;">
                <span style="font-weight: bold; font-size: 14pt;">
                    No. Factura
                    <br>
                    F2MC {{ $invoice->number }}
                </span>
                @if (strlen($invoice->company->billing_resolution) > 1)
                <br>
                Resolución de Facturación No.
                <br>
                {{ $invoice->company->billing_resolution }} de
                {{ \Carbon\Carbon::parse($invoice->company->billing_date)->format("d/m/Y") }}
                <br>
                Desde {{ $invoice->company->billing_start }} hasta el {{ $invoice->company->billing_end }}
                @endif
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
<div style="text-align: right">Fecha de elaboración: {{ \Carbon\Carbon::parse($createdAt)->format("d/m/Y") }}</div>
    <table width="100%" style="font-family: serif;" cellpadding="10">
        <tr>
            @if ($invoice->multiple)
              @php
                $firstAuthorizationCode = json_decode($invoice->multiple_codes, true)[0];
                $firstAuthorization = \App\Authorization::findByCode($firstAuthorizationCode);
              @endphp
              <td width="34%" style="border: 0.1mm solid #888888; ">
                  <span style="font-size: 9pt; color: #555555; font-family: sans;">
                      DATOS DEL PACIENTE:
                  </span>
                  <br><br>
                  Tipo de Documento: {{ $firstAuthorization->patient->dni_type}}
                  <br>
                  Número de Documento: {{ $firstAuthorization->patient->dni}}
                  <br>
                  {{ $firstAuthorization->patient->full_name}}
                  <br>
              </td>
              <td width="1%">&nbsp;</td>
              <td width="34%" style="border: 0.1mm solid #888888;">
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
            <td width="11%">Codigo</td>
            <td width="32%">Detalle</td>
            <td width="20%">Autorizacion</td>
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
                    $currentAuthorization = \App\Authorization::findByCode($authorizationCode);
                    $services = $currentAuthorization->eps_service_id;
                    $companionService = $currentAuthorization->multiple_services;
                    $flag = false;
                    if ($companionService) {
                        $services .= ",".$companionService;
                    } elseif (count($currentAuthorization->services) > 0) {
                        $services = '';
                        foreach ($currentAuthorization->services as $servicePrice) {
                            $services .= $servicePrice->eps_service_id.",";
                        }
                        $flag = true;
                        $services = trim($services, ",");
                    }
                @endphp
                @if ($flag)
                    @foreach($currentAuthorization->services as $authorizationService)
                        @php
                            $currentTotal = $authorizationService->price * $authorizationService->days;
                            $total += $currentTotal;
                        @endphp
                        <tr>
                            <td align="center">{{ $authorizationService->service->code }}</td>
                            <td>{{ $authorizationService->service->name }}</td>
                            <td>{{ $currentAuthorization->codec }}</td>
                            <td align="center">{!! $authorizationService->days !!}</td>
                            <td class="cost">$ {{ number_format($authorizationService->price, 0, ",", ".") }}</td>
                            <td class="">$ {!! number_format($currentTotal, 0, ",", ".") !!}</td>
                        </tr>
                    @endforeach
                @else
                    @foreach(explode(",", $services) as $serviceId)
                        @php
                            $service = \App\EpsService::find($serviceId);
                            $currentTotal = json_decode($invoice->multiple_totals, true)[$k] / count(explode(",", $services));
                            $total += $currentTotal;
                        @endphp
                        <tr>
                            <td align="center">{{ $service->code }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ $currentAuthorization->codec }}</td>
                            <td align="center">{!! json_decode($invoice->multiple_days, true)[$k] !!}</td>
                            <td class="cost">$ {{ number_format($service->price > 0 ? $service->price : $currentAuthorization->daily_price / count(explode(",", $services)), 0, ",", ".") }}</td>
                            <td class="">$ {!! number_format($currentTotal, 0, ",", ".") !!}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        @else
            @php
                $total = 0;
                $services = $invoice->authorization->eps_service_id;
                $companionService = $invoice->authorization->multiple_services;
                $flag = false;
                if ($companionService) {
                    $services .= ",".$companionService;
                } elseif (count($invoice->authorization->services) > 0) {
                    $services = '';
                    $flag = true;
                    foreach ($invoice->authorization->services as $servicePrice) {
                        $services .= $servicePrice->eps_service_id.",";
                    }
                    $services = trim($services, ",");
                }
            @endphp
            @if ($flag)
                @foreach($invoice->authorization->services as $authorizationService)
                    @php
                        $currentTotal = $authorizationService->price * $authorizationService->days;
                        $total += $currentTotal;
                    @endphp
                    <tr>
                        <td align="center">{{ $authorizationService->service->code }}</td>
                        <td>{{ $authorizationService->service->name }}</td>
                        <td>{{ $invoice->authorization->codec }}</td>
                        <td align="center">{!! $authorizationService->days !!}</td>
                        <td class="cost">$ {{ number_format($authorizationService->price, 0, ",", ".") }}</td>
                        <td class="">$ {!! number_format($currentTotal, 0, ",", ".") !!}</td>
                    </tr>
                @endforeach
            @else
                @foreach(explode(",", $services) as $serviceId)
                    @php
                        $service = \App\EpsService::find($serviceId);
                        $total += $invoice->total / count(explode(",", $services));
                    @endphp
                    <tr>
                        <td align="center">{{ $service->code }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ $invoice->authorization->codec }}</td>
                        <td align="center">{!! $invoice->days !!}</td>
                        <td class="cost">$ {{ number_format($service->price > 0 ? $service->price : $invoice->authorization->daily_price / count(explode(",", $services)), 0, ",", ".") }}</td>
                        <td class="cost">$ {!! number_format($invoice->total / count(explode(",", $services)), 0, ",", ".") !!}</td>
                    </tr>
                @endforeach
            @endif
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
                <img src="{{ asset('img/signature.png') }}" alt="" height="40" style="margin-bottom: -17px;">
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
