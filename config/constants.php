<?php

$myConstants = [
    'unathorized' => [
        'prefix' => 'SA',
    ],
    'emails' => [
        'testing' => 'atpjulio@gmail.com',
        'admin'   => 'fundacasahogar_elmilagro@hotmail.com',
        'admin2'  => 'elmilagrobq1@gmail.com'
    ],
    'pagination'      => 20,
    'stylesVersion'   => '2.93',
    'productImages'   => '/img/products/',
    'usersImages'     => '/img/users/',
    'companiesImages' => '/img/companies/',
    'importFiles'     => storage_path().'/app/',
    'ripsFiles'       => 'public/rips/',
    'ripsExtensions'  => [
        'AF' => '.TXT',
        'AT' => '.TXT',
        'CT' => '.TXT',
        'US' => '.TXT',
        'RIP' => '.xls',
        'RIPS' => '.zip',
    ],
    'citiesFilename' => 'codigos_dane.xls',
    'pucsFilename' => 'puc_comerciantes.xls',
    'systemUser' => [
        'id' => 1,
        'email' => 'atpjulio@gmail.com',
        'first_name' => 'Julio',
        'last_name' => 'Amaya',
    ],
    'modelType' => [
        'company' => 1,
        'eps' => 2,
        'patient' => 3,
    ],
    'modelTypeString' => [
        1 => 'Company',
        2 => 'Eps',
        3 => 'Patient',
    ],
    'userRoles' => [
        'user' => 1,
        'accountant' => 2,
        'admin' => 9,
    ],
    'userRolesString' => [
        1 => 'user',
        2 => 'accountant',
        9 => 'admin',
    ],
    'noYes' => [
        0 => 'No',
        1 => 'Si',
    ],
    'residenceZone' => [
        'U' => 'Urbana',
        'R' => 'Rural'
    ],
    'gender' => [
        0 => 'Femenino',
        1 => 'Masculino',
    ],
    'genderShort' => [
        0 => 'F',
        1 => 'M',
    ],
    'patientTypeString' => [
        1 => 'Contributivo',
        2 => 'Subsidiado',
        3 => 'Vinculado',
        4 => 'Particular',
        5 => 'Otro',
        6 => 'Desplazado Contributivo',
        7 => 'Desplazado Subsidiado',
        8 => 'Desplazado No Asegurado',
    ],
    'patient' => [
        'location' => [
            0 => 'Hospedaje',
            1 => 'Clínica',
            2 => 'Unidad UCI',
            3 => 'Habitación',
        ],
    ],
    'userRolesFrontEnd' => [
        1 => 'Usuario',
        9 => 'Administrador',
    ],
    'notifications' => [
        'status' => [
            'unread' => 0,
            'read' => 1,
        ],
        'type' => [
            'success' => 1,
            'warning' => 2,
            'danger' => 3,
            'info' => 4,
        ],
    ],
    'invoices' => [
        'status' => [
            'pending' => 0,
            'paid' => 1,
        ],
        'statusString' => [
            0 => 'Pendiente',
            1 => 'Pagada',
        ],
        'action' => [
            'create' => 0,
            'edit' => 1,
            'delete' => 2,
            'payment' => 3,
            'fullPayment' => 4,
        ],
        'actionString' => [
            0 => 'Creación',
            1 => 'Edición',
            2 => 'Eliminación',
            3 => 'Abono',
            4 => 'Pago Completo',
        ],
    ],
    'status' => [
        'active' => 1,
        'inactive' => 9,
    ],
    'documentTypes' => [
        "CC" => "CC - Cédula de Ciudadanía",
        "TI" => "TI - Tarjeta de Identidad",
        "CE" => "CE - Cédula de Extranjería",
        "PA" => "PA - Pasaporte",
        "RC" => "RC - Registro Civil",
        "CN" => "CN - Certificado de Nacimiento",
        "AS" => "AS - Adulto Sin Identificación",
        "MS" => "MS - Menor Sin Identificación",
        "NU" => "NU - Número Unico de Identificación",
        "PE" => "PE - Permiso Especial",
        "SC" => "SC - Salvo Conducto",
    ],
    'companiesDocumentTypes' => [
        "NI" => "NIT",
        "CC" => "Cédula de Ciudadanía",
        "CE" => "Cédula de Extranjería",
        "PA" => "Pasaporte",
    ],
    'companyInfo' => [
        'name'           => 'Fundación',
        'email'          => 'admin@fundacion.com',
        'urlName'        => 'fundacion',
        'longName'       => 'Fundación Multiactiva Casa Hogar el Milagro',
        'description'    => 'Casa de Paso',
        'phoneNumber'    => '+57 3126214231',
        'logo'           => env('APP_URL').'img/logo.png',
        'longLogo'       => 'http://www.casahogarelmilagro.com/img/logo.png',
        'invoicePreffix' => 'F2MC'
    ],
    'months' => [
        1  => 'Enero',
        2  => 'Febrero',
        3  => 'Marzo',
        4  => 'Abril',
        5  => 'Mayo',
        6  => 'Junio',
        7  => 'Julio',
        8  => 'Agosto',
        9  => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ],
    'banks' => [
        1 => 'Bancolombia',
        2 => 'Bancoomeva',
        3 => 'AV Villas',
        4 => 'Otro',
    ],
    'paymentType' => [
        1 => 'Efectivo',
        2 => 'Transferencia',
    ],
    'exportMethods' => [
        'relation'    => 0,
        'volume'      => 1,
        'worldOffice' => 2,
        'excel'       => 3,
        'options'        => [
            0 => 'Relación PDF',
            1 => 'Volumen PDF',
            2 => 'WorldOffice',
            3 => 'Excel',
        ],
    ],
];

return $myConstants;
