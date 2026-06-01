<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?error=auth_required');
    exit;
}

$login = $_SESSION['login'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Личный кабинет</h1>

    <p>Вы успешно вошли в систему.</p>

    <p><b>ID пользователя:</b> <?php echo $_SESSION['user_id']; ?></p>
    <p><b>Логин:</b> <?php echo htmlspecialchars($login); ?></p>
    <p><b>Имя:</b> <?php echo htmlspecialchars($name); ?></p>

    <a class="logout" href="logout.php">Выйти</a>
</div>

</body>
</html>