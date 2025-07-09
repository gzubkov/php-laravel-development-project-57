<?php

return [

    'appname' => 'Менеджер задач',
    'tasks' => 'Задачи',
    'task_statuses' => 'Статусы',
    'labels' => 'Метки',

    'task_status' => 'статус',
    'Task_status' => 'Статус',
    'task' => 'задача',
    'label' => 'метка',
    
    'messages' => [
        'task_status' => [
            'create_success' => 'Статус успешно создан',
            'update_success' => 'Статус успешно изменён',
            'delete_success' => 'Статус успешно удалён',
            'delete_failed' => 'Не удалось удалить статус',
        ],

        'task' => [
            'create_success' => 'Задача успешно создана',
            'update_success' => 'Задача успешно изменена',
            'delete_success' => 'Задача успешно удалена',
        ],
            
        'label'  => [
            'create_success' => 'Метка успешно создана',
            'update_success' => 'Метка успешно изменена',
            'delete_success' => 'Метка успешно удалена',
            'delete_failed' => 'Не удалось удалить метку',
        ],
    ],

    'auth' => [
        'login' => 'Вход',
        'logout' => 'Выход',
        'register' => 'Регистрация',
    ],

    'validation' => [
        //'required' => 'Поле ":field" обязательно для заполнения',
        'required' => 'Это обязательное поле',
        'unique' => 'Поле ":field" должно быть уникальным',
        'uniquemodule' => ':Module с таким именем уже существует',
    ],

    'actions' => [
        'actions' => 'Действия',
        'delete' => 'Удалить',
        'edit' => 'Изменить',
        'create' => 'Создать',
        'create_module' => 'Создать :module',
        'update' => 'Обновить',
        'apply' => 'Применить',

        'confirm' => 'Вы уверены?',

        'task_status' => [
            'create' => 'Создать статус',
            'edit' => 'Изменение статуса',
        ],

        'task' => [
            'create' => 'Создать задачу',
            'edit' => 'Изменение задачи',
            'show' => 'Просмотр задачи',
        ],

        'label' => [
            'create' => 'Создать метку',
            'edit' => 'Изменение метки',
        ],
    ],

    'fields' => [
        'name' => 'Имя',
        'date_created' => 'Дата создания',
        'author' => 'Автор',
        'contractor' => 'Исполнитель',
        'description' => 'Описание',
    ],

    'hexlet' => [
        'intro' => 'Привет от Хекслета!',
        'description' => 'Это простой менеджер задач на Laravel',
        'button' => 'Нажми меня'
    ]
];