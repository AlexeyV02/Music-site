<?php
require_once 'includes/config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $name = trim($_POST['name']);
    
    // Проверка на существующего пользователя
    $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->execute([$login]);
    
    if ($stmt->rowCount() > 0) {
        $error = 'Пользователь с таким логином уже существует';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (login, password, name) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$login, $hashed_password, $name])) {
            $success = 'Регистрация успешна! Теперь вы можете войти.';
        } else {
            $error = 'Ошибка при регистрации';
        }
    }
}

require_once 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <h2 class="text-center mb-4">Регистрация</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
            <div class="text-center">
                <a href="login.php" class="btn btn-primary">Перейти к входу</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="login" class="form-label">Логин</label>
                    <input type="text" class="form-control" id="login" name="login" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Зарегистрироваться</button>
            </form>
            
            <p class="mt-3 text-center">
                Уже есть аккаунт? <a href="login.php">Войдите</a>
            </p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>