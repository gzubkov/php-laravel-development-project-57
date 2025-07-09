<?php

return [
    'required'  => 'Это обязательное поле',
    'unique'    => 'Поле с таким именем уже существует',
    'confirmed' => ':attribute и подтверждение не совпадают.',
    'email' => ':attribute введён некорректно.',

    'min' => [
        'string' => ':attribute должен иметь длину не менее :min символов.',
    ],
    'attributes' => [
        'password' => 'Пароль',
    ],
];
