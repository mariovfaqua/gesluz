<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'email' => 'El campo :attribute debe ser un correo válido.',
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'max' => [
        'string' => 'El campo :attribute no debe exceder de :max caracteres.',
    ],
    'custom' => [
        'phone' => [
            'required' => 'El número de teléfono es obligatorio.',
            'min' => 'El teléfono debe tener al menos :min caracteres.',
            'max' => 'El teléfono no puede tener más de :max caracteres.',
        ],
    ],
    'attributes' => [
        'phone' => 'teléfono',
    ],
];
