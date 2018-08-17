<?php

$myConstants = [
    'emails' => [
        'testing' => 'atpjulio@gmail.com',
        'admin' => 'arielcanario@hotmail.com'
    ],
    'stylesVersion' => '1.000',
    'productImages' => '/img/products/',
    'usersImages' => '/img/users/',
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
    'status' => [
        'active' => 1,
        'inactive' => 9,
    ],
    'pagination' => 1000,
    'documentTypes' => [
        "CC" => "Cédula de Ciudadanía",
        "TI" => "Tarjeta de Identidad",
        "CE" => "Cédula de Extranjería",
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