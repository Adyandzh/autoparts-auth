<?php
session_start();

require_once 'users.php';
require_once 'logger.php';

$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($login === '' || $password === '') {
    writeLog($login, 'FAIL_LOGIN', 'empty_fields');
    header('Location: index.php?error=empty');
    exit;
}

if (!isset($users[$login])) {
    writeLog($login, 'FAIL_LOGIN', 'user_not_found');
    header('Location: index.php?error=invalid');
    exit;
}

$user = $users[$login];

if (password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['login'] = $login;
    $_SESSION['name'] = $user['name'];

    writeLog($login, 'SUCCESS_LOGIN');

    header('Location: dashboard.php');
    exit;
} else {
    writeLog($login, 'FAIL_LOGIN', 'wrong_password');
    header('Location: index.php?error=invalid');
    exit;
}