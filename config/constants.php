<?php

$myConstants = [
    'emails' => [
        'testing' => 'atpjulio@gmail.com',
        'admin' => 'arielcanario@hotmail.com'
    ],
    'stylesVersion' => '1.000',
    'productImages' => '/img/products/',
    'usersImages' => '/img/users/',
    'companiesImages' => '/img/companies/',
    'importFiles' => storage_path().'/app/',
    'ripsFiles' => 'public/rips/',
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
        'admin' => 9,
    ],
    'userRolesString' => [
        1 => 'user',
        9 => 'admin',
    ],
    'noYes' => [
        0 => 'No',
        1 => 'Si',
    ],
    'gender' => [
        0 => 'Femenino',
        1 => 'Masculino',
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
    'pagination' => 1000,
    'documentTypes' => [
        "CC" => "Cédula de Ciudadanía",
        "TI" => "Tarjeta de Identidad",
        "CE" => "Cédula de Extranjería",
        "PA" => "Pasaporte",
        "RC" => "Registro Civil",
        "AS" => "Adulto Sin Identificación",
        "MS" => "Menor Sin Identificación",
        "NU" => "Número Unico de Identificación",
    ],
    'companyInfo' => [
        'name' => 'Fundacion',
        'email' => 'admin@fundacion.com',
        'urlName' => 'fundacion',
        'longName' => 'Fundación Multiactiva Casa Hogar el Milagro',
        'description' => 'Casa de Paso',
        'phoneNumber' => '+57 3126214231',
        'logo' => env('APP_URL').'img/logo.png',
    ],
    'months' => [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ],
];

return $myConstants;