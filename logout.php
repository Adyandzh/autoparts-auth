<?php
session_start();

require_once 'logger.php';

$login = $_SESSION['login'] ?? 'unknown';

writeLog($login, 'LOGOUT');

$_SESSION = [];

session_destroy();

header('Location: index.php');
exit;