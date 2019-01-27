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
                    {{ $egress->company->name }}
                </span>
                <br>
                <strong>
                {{ config('constants.companiesDocumentTypes')[$egress->company->doc_type] }}:
                </strong> 
                {{ $egress->company->doc }}
                <br><strong>Dirección:</strong> Carrera 60 No. 46 - 76
                <br><strong>Tel:</strong> 3126214231 - 3157098010
                <br>
            </td>
            <td width="45%" style="text-align: right;">
                <span style="font-weight: bold; font-size: 14pt;">
                    No. Comprobante
                    <br>
                    {{ $egress->number }}
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
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
    <!--
    <div style="text-align: right; font-size: 12pt;">
        Total: $ {{ number_format($egress->amount, 0, ",", ".") }}
    </div>
    -->
    <div style="text-align: right">Fecha de elaboración: {{ \Carbon\Carbon::parse($egress->created_at)->format("d/m/Y") }}</div>
    <table width="100%" style="font-family: serif;" cellpadding="10">
        <tr>
            <td width="30%" style="border: 0.1mm solid #888888; ">
                <span style="font-size: 9pt; color: #555555; font-family: sans;">
                    PAGADO A:
                </span>
                <br><br>
                {{ $egress->entity->name }}
            </td>
            <td width="1%"></td>
            <td width="30%" style="border: 0.1mm solid #888888; ">
                <span style="font-size: 9pt; color: #555555; font-family: sans;">
                    SON:
                </span>
                <br><br>
                {{ ucfirst(\App\NumberToLetter::convertirEurosEnLetras(number_format($egress->amount, 0, ",", "."), 0)) }} M/L
            </td>
            <td width="1%"></td>
            <td width="38%" style="border: 0.1mm solid #888888; ">
                <span style="font-size: 9pt; color: #555555; font-family: sans;">
                    POR CONCEPTO DE:
                </span>
                <br><br>
                {{ $egress->concept }}
            </td>
        </tr>
    </table>
    <br>
    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; border-bottom-width: 0; border-left-width: 0;" cellpadding="8">
        <thead>
        <tr>
            <td width="10%">Codigo</td>
            <td width="35%">Cuenta</td>
            <td width="18%">Debitos</td>
            <td width="7%">Creditos</td>
        </tr>
        </thead>
        <tbody>
        <!-- ITEMS HERE -->
        @foreach($egress->pucs as $puc)
        <tr>
            <td align="center">{{ $puc->code }}</td>
            <td>{{ $puc->description }}</td>
            <td class="cost">{!! !$puc->type ? '$ '.number_format($puc->amount, 0, ",", ".") : "--" !!}</td>
            <td class="cost">{!! $puc->type ? '$ '.number_format($puc->amount, 0, ",", ".") : "--" !!}</td>
        </tr>
        @endforeach
        <!-- END ITEMS HERE -->
        <!-- END ITEMS HERE -->
        <tr style="border-bottom-width: 0;">
            <td class="blanktotal" colspan="2"></td>
            <td class="totals"><b>TOTAL:</b></td>
            <td class="totals cost">
                <b>
                $ {!! number_format($egress->amount, 0, ",", ".") !!}
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
        Comprobante de egreso generado automáticamente
    </div>
</body>
</html>