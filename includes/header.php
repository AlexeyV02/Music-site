<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Музыкальный сайт</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-music"></i> MusicSite</a>
            <div class="navbar-nav ms-auto">
                <?php if (isLoggedIn()): ?>
                    <span class="nav-item nav-link">Привет, <?= htmlspecialchars($_SESSION['user_name']) ?>!</span>
                    <a class="nav-item nav-link" href="admin.php"><i class="fas fa-cog"></i> Панель админа</a>
                    <a class="nav-item nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Выйти</a>
                <?php else: ?>
                    <a class="nav-item nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Войти</a>
                    <a class="nav-item nav-link" href="register.php"><i class="fas fa-user-plus"></i> Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">