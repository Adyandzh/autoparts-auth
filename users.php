<?php

$users = [
    'admin' => [
        'id' => 1,
        'name' => 'Администратор',
        'password_hash' => password_hash('admin123', PASSWORD_DEFAULT)
    ],
    'user' => [
        'id' => 2,
        'name' => 'Пользователь',
        'password_hash' => password_hash('user123', PASSWORD_DEFAULT)
    ]
];