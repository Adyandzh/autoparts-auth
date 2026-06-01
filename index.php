<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Авторизация</h1>

    <?php if ($error === 'empty'): ?>
        <p class="error">Заполните логин и пароль.</p>
    <?php elseif ($error === 'invalid'): ?>
        <p class="error">Неверный логин или пароль.</p>
    <?php elseif ($error === 'auth_required'): ?>
        <p class="error">Сначала выполните вход.</p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label>Логин:</label>
        <input type="text" name="login" placeholder="Введите логин">

        <label>Пароль:</label>
        <input type="password" name="password" placeholder="Введите пароль">

        <button type="submit">Войти</button>
    </form>

    <div class="hint">
        <p><b>Тестовые данные:</b></p>
        <p>Логин: <b>admin</b> Пароль: <b>admin123</b></p>
        <p>Логин: <b>user</b> Пароль: <b>user123</b></p>
    </div>
</div>

</body>
</html>