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
            /*border-right: 0.1mm solid #000000;*/
        }
        .items .noleft {
            border-left-width: 0;
        }
        table thead td { background-color: #DCDCDC;
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
            /*text-align: "." center;*/
            text-align: right;
        }
    </style>
</head>
<body>
<!--mpdf
<htmlpageheader name="myheader">
    <table width="100%">
        <tr>
            <td width="15%" style="color:#0000BB; ">
                <img src="{{ $company->logo }}" height="100px">
            </td>
            <td width="40%" >
                <span style="font-weight: bold; font-size: 14pt;">
                    {{ $company->name }}
                </span>
                <br>
                <strong>
                {{ config('constants.companiesDocumentTypes')[$company->doc_type] }}:
                </strong>
                {{ $company->doc }}
                <br><strong>Dirección:</strong> {{ $company->address->full_address }}
                <br><strong>Tel:</strong> {{ $company->phone->full_phone }}
                <br>
            </td>
            <td width="45%" style="text-align: right;">
                <span style="font-weight: bold; font-size: 14pt;">
                    Relación de Facturas
                    <br>
                    {{ $eps->alias }}
                </span>
                <br><br>
                Del {{ \Carbon\Carbon::parse($initialDate)->format("d/m/Y") }} al
                {{ \Carbon\Carbon::parse($finalDate)->format("d/m/Y") }}
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
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div style="text-align: right">Fecha de elaboración: {{ \Carbon\Carbon::parse($createdAt)->format("d/m/Y") }}</div>
{{--
    <table width="100%" style="font-family: serif;" cellpadding="10">
        <tr>
            <td width="45%" style="border: 0.1mm solid #888888; ">
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
            <td width="10%">&nbsp;</td>
            <td width="45%" style="border: 0.1mm solid #888888;">
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
        </tr>
    </table>
    <br>
--}}
    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; border-bottom-width: 0; border-left-width: 0;" cellpadding="8">
        <thead>
        <tr>
            <td width="13%">No. Factura</td>
            <td width="62%" colspan="3">Datos del afiliado</td>
            <td width="10%">Cant.</td>
            <td width="15%">Valor Facturas</td>
        </tr>
        </thead>
        <tbody>
        <!-- ITEMS HERE -->
        @php
            $total = 0;
        @endphp
        @foreach ($invoices as $invoice)
        @if ($invoice->multiple)
            @php
                $subTotal = 0;
            @endphp
            @foreach (json_decode($invoice->multiple_codes, true) as $k => $authorizationCode)
                <tr>
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
                    <td align="center">{{ $invoice->format_number }}</td>
                    <td width="14%" class="noleft">{!! $currentAuthorization->patient->dni !!}</td>
                    <td colspan="2" class="noleft">
                        {!! mb_strtoupper($currentAuthorization->patient->full_name) !!}
                    </td>
                    <td class="noleft"></td>
                    <td class="noleft"></td>
                </tr>

                @if ($flag)
                    @foreach($currentAuthorization->services as $authorizationService)
                        @php
                            $currentTotal = $authorizationService->price * $authorizationService->days;
                            $subTotal += $currentTotal;
                        @endphp
                        <tr>
                            <td align="right">&#8250; {!!  $authorizationService->service->code !!}</td>
                            <td colspan="2" class="noleft">{{ mb_strtoupper($authorizationService->service->name) }}</td>
                            <td style="width: 18%;" class="noleft">{{ mb_strtoupper($currentAuthorization->codec) }}</td>
                            <td align="center" class="noleft">
                                {!! $authorizationService->days !!}
                            </td>
                            <td class="cost noleft">
                                $ {!! number_format($currentTotal, 0, ",", ".") !!}
                            </td>
                        </tr>
                    @endforeach
                @else
                    @foreach(explode(",", $services) as $serviceId)
                        @php
                            $service = \App\EpsService::find($serviceId);
                            $currentTotal = json_decode($invoice->multiple_totals, true)[$k] / count(explode(",", $services));
                            $subTotal += $currentTotal;
                        @endphp
                        <tr>
                            <td align="right">&#8250; {!! $service->code !!}</td>
                            <td colspan="2" class="noleft">{{ mb_strtoupper($service->name) }}</td>
                            <td style="width: 18%;" class="noleft">{{ mb_strtoupper($currentAuthorization->codec) }}</td>
                            <td align="center" class="noleft">
                                {!! json_decode($invoice->multiple_days, true)[$k] !!}
                            </td>
                            <td class="cost noleft">
                                $ {!! number_format($currentTotal, 0, ",", ".") !!}
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        @else
            <tr>
                <td align="center">{{ $invoice->format_number }}</td>
                <td width="14%" class="noleft">
                    {!! $invoice->authorization->patient->dni !!}
                </td>
                <td colspan="2" class="noleft">
                    {{ mb_strtoupper($invoice->authorization->patient->last_name.' '.$invoice->authorization->patient->first_name) }}
                </td>
                <td class="noleft"></td>
                <td class="noleft"></td>
            </tr>
            @php
                $subTotal = 0;
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
                    @endphp
                    <tr>
                        <td align="right">&#8250; {!!  $authorizationService->service->code !!}</td>
                        <td colspan="2" class="noleft">{{ mb_strtoupper($authorizationService->service->name) }}</td>
                        <td style="width: 18%;" class="noleft">{{ mb_strtoupper($invoice->authorization->codec) }}</td>
                        <td align="center" class="noleft">
                            {!! $authorizationService->days !!}
                        </td>
                        <td class="cost noleft">
                            $ {!! number_format($currentTotal, 0, ",", ".") !!}
                        </td>
                    </tr>
                @endforeach
            @else
                @foreach(explode(",", $services) as $serviceId)
                    @php
                        $service = \App\EpsService::find($serviceId);
                        $currentTotal = $invoice->total / count(explode(",", $services));
                    @endphp
                    <tr>
                        <td align="right"> &#8250; {{ $service->code }}</td>
                        <td colspan="2" class="noleft">{{ mb_strtoupper($service->name) }}</td>
                        <td style="width: 18%;" class="noleft">{!! $invoice->authorization->codec !!}</td>
                        <td align="center" class="noleft">{!! $invoice->days !!}</td>
                        <td class="cost noleft">$ {!! number_format($currentTotal, 0, ",", ".") !!}</td>
                    </tr>
                @endforeach
            @endif
            @php
                $subTotal += $invoice->total;
            @endphp
        @endif
            <tr style="background-color: #EEEEEE;">
                <td colspan="5" style="font-variant: small-caps;">
                    Subtotal Factura
                </td>
                <td class="cost noleft">$ {!! number_format($subTotal, 0, ",", ".") !!}</td>
            </tr>
            @php
                $total += $subTotal;
            @endphp
        @endforeach
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
        {!! $company->name." - Relación de Facturas ".$eps->alias !!}
    </div>
</body>
</html>
