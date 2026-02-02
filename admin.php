<?php
require_once 'includes/config.php';

// Только для админа
if (!isAdmin()) {
    header('Location: index.php');
    exit;
}

// Удаление трека
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tracks WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin.php?message=deleted');
    exit;
}

// Добавление трека
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $description = $_POST['description'];
    $lyrics = $_POST['lyrics'];
    
    // Загрузка обложки
    $cover_image = '';
    if (!empty($_FILES['cover']['name'])) {
        $cover_name = time() . '_' . basename($_FILES['cover']['name']);
        $cover_target = "uploads/images/" . $cover_name;
        if (move_uploaded_file($_FILES['cover']['tmp_name'], $cover_target)) {
            $cover_image = $cover_target;
        }
    }
    
    // Загрузка аудио
    $audio_file = '';
    if (!empty($_FILES['audio']['name'])) {
        $audio_name = time() . '_' . basename($_FILES['audio']['name']);
        $audio_target = "uploads/audio/" . $audio_name;
        if (move_uploaded_file($_FILES['audio']['tmp_name'], $audio_target)) {
            $audio_file = $audio_target;
        }
    }
    
    if ($audio_file) {
        $stmt = $pdo->prepare("
            INSERT INTO tracks (title, artist, description, lyrics, cover_image, audio_file, added_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$title, $artist, $description, $lyrics, $cover_image, $audio_file, $_SESSION['user_id']]);
        $message = 'Трек успешно добавлен!';
    } else {
        $message = 'Ошибка загрузки аудиофайла';
    }
}

// Получаем все треки
$stmt = $pdo->query("SELECT * FROM tracks ORDER BY id DESC");
$tracks = $stmt->fetchAll();
?>

<?php require_once 'includes/header.php'; ?>

<h1 class="mb-4"><i class="fas fa-cog"></i> Панель администратора</h1>

<?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success">Трек удален!</div>
<?php endif; ?>

<?php if ($message): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <h3>Добавить новый трек</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Название трека</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label>Исполнитель</label>
                <input type="text" name="artist" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label>Описание</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="mb-3">
                <label>Текст песни</label>
                <textarea name="lyrics" class="form-control" rows="6"></textarea>
            </div>
            
            <div class="mb-3">
                <label>Обложка (JPG/PNG)</label>
                <input type="file" name="cover" class="form-control" accept="image/*">
            </div>
            
            <div class="mb-3">
                <label>Аудиофайл (MP3)</label>
                <input type="file" name="audio" class="form-control" accept="audio/*" required>
            </div>
            
            <button type="submit" class="btn btn-success">
                <i class="fas fa-plus"></i> Добавить трек
            </button>
        </form>
    </div>
    
    <div class="col-md-6">
        <h3>Все треки (<?= count($tracks) ?>)</h3>
        <div class="list-group">
            <?php foreach ($tracks as $track): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($track['title']) ?></strong>
                        <br>
                        <small><?= htmlspecialchars($track['artist']) ?></small>
                    </div>
                    <div>
                        <a href="track.php?id=<?= $track['id'] ?>" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="admin.php?delete=<?= $track['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Удалить?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>