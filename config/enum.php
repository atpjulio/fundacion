<?php

$myConstants = [
  'gender' => [
    'enum' => [
      'female' => 'F',
      'male'   => 'M',
    ],
    'text' => [
      'F' => 'Femenino',
      'M' => 'Masculino',
    ],
  ],
  'patient' => [
    'type' => [
      'enum' => [
        'subsidized'             => 'SUBSIDIZED',
        'contributory'           => 'CONTRIBUTORY',
        'linked'                 => 'LINKED',
        'particular'             => 'PARTICULAR',
        'other'                  => 'OTHER',
        'displaced_contributory' => 'DISPLACED_CONTRIBUTORY',
        'displaced_subsidized'   => 'DISPLACED_SUBSIDIZED',
        'displaced_uninsured'    => 'DISPLACED_UNINSURED',
      ],
      'text' => [
        'SUBSIDIZED'             => 'Contributivo',
        'CONTRIBUTORY'           => 'Subsidiado',
        'LINKED'                 => 'Vinculado',
        'PARTICULAR'             => 'Particular',
        'OTHER'                  => 'Otro',
        'DISPLACED_CONTRIBUTORY' => 'Desplazado Contributivo',
        'DISPLACED_SUBSIDIZED'   => 'Desplazado Subsidiado',
        'DISPLACED_UNINSURED'    => 'Desplazado No Asegurado',
      ],
    ],
    'location' => [
      0 => 'Hospedaje',
      1 => 'Clínica',
      2 => 'Unidad UCI',
      3 => 'Habitación',
    ],
    'zone' => [
      'enum' => [
        'urban' => 'U',
        'rural' => 'R'
      ],
      'text' => [
        'U' => 'Urbana',
        'R' => 'Rural'
      ],
    ],
  ],
  'invoice' => [
    'status' => [
      'created' => 'CREATED',
      'sent'    => 'SENT',
      'paid'    => 'PAID',
      'return'  => 'RETURN',
    ]
  ],
  'status' => [
    'active'   => 'ACTIVE',
    'inactive' => 'INACTIVE',
  ],
];

return $myConstants;
