<body>
    <div style="background:#d6ebf3;padding:20px;">
        <table style="font-size: 12px; font-family: 'Karla', sans-serif; border: 1px solid #000000; line-height: 18px; color: #555;" border="0" width="600" cellspacing="0" cellpadding="0" align="center">
            <thead>
                <tr>
                    <th style="background-color: #21d06b; border-top: 1px solid #21d06b; font-size: 20px; color:#FFFFFF; height: 23px;padding:10px" align="center">
                        <p><img src="{{ config('constants.companyInfo.longLogo') }}" height="119"/></p>
                        <p>{{ config('constants.companyInfo.longName') }}</p>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 10px;background-color:#FFFFFF" align="left;">
                        <p>&nbsp;</p>
                        <br>
                        {!! $content !!}
                        <br>
                        <p>Saludos,</p>
                        <p>El Equipo de {{ config('constants.companyInfo.longName') }}</p>
                        <p>&nbsp;</p>
                    </td>
                </tr>
            </tbody>
            <tfoot>
            <tr>
                <td style="background-color: #4b479a; border-top: 1px solid #4b479a; font-size: 10px; color:#ffffff;height: 23px;" align="center">
                    <a style="color:#ffffff;" href="{{ env('APP_URL') }}">{{ env('APP_URL') }}</a> | {{config('constants.companyInfo.phoneNumber')}}
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <p>&nbsp;</p>
</body>
</html>

