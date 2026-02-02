<?php
$host = 'localhost';
$db   = 'music_site';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Пароль: admin123
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Удаляем старого администратора
    $pdo->exec("DELETE FROM users WHERE login = 'admin'");
    
    // Добавляем нового
    $stmt = $pdo->prepare("INSERT INTO users (login, password, name) VALUES (?, ?, ?)");
    $stmt->execute(['admin', $hashed_password, 'Администратор']);
    
    echo "Администратор создан успешно!<br>";
    echo "Логин: admin<br>";
    echo "Пароль: admin123<br>";
    echo "Хеш пароля: " . $hashed_password;
    
} catch(PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>