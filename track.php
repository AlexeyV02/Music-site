<?php
require_once 'includes/config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT t.*, u.name as added_by_name 
    FROM tracks t 
    LEFT JOIN users u ON t.added_by = u.id 
    WHERE t.id = ?
");
$stmt->execute([$_GET['id']]);
$track = $stmt->fetch();

if (!$track) {
    header('Location: index.php');
    exit;
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-4">
        <img src="<?= $track['cover_image'] ?: 'images/default-cover.jpg' ?>" 
             class="img-fluid rounded" 
             alt="<?= htmlspecialchars($track['title']) ?>">
    </div>
    
    <div class="col-md-8">
        <h1><?= htmlspecialchars($track['title']) ?></h1>
        <h4 class="text-muted"><?= htmlspecialchars($track['artist']) ?></h4>
        
        <hr>
        
        <div class="mb-4">
            <h5>Описание</h5>
            <p><?= nl2br(htmlspecialchars($track['description'] ?: 'Нет описания')) ?></p>
        </div>
        
        <div class="mb-4">
            <h5>Текст песни</h5>
            <div class="lyrics-box p-3 bg-light rounded">
                <?= nl2br(htmlspecialchars($track['lyrics'] ?: 'Текст не добавлен')) ?>
            </div>
        </div>
        
        <div class="mb-3">
            <p><strong>Добавлено:</strong> <?= $track['added_by_name'] ?> (<?= $track['added_at'] ?>)</p>
        </div>
        
        <button class="btn btn-lg btn-primary play-btn" 
                data-audio="<?= htmlspecialchars($track['audio_file']) ?>"
                data-title="<?= htmlspecialchars($track['title']) ?>"
                data-artist="<?= htmlspecialchars($track['artist']) ?>">
            <i class="fas fa-play-circle"></i> Воспроизвести трек
        </button>
        
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Назад к списку
        </a>
        
        <?php if (isAdmin()): ?>
            <a href="admin.php?delete=<?= $track['id'] ?>" 
               class="btn btn-danger" 
               onclick="return confirm('Удалить этот трек?')">
                <i class="fas fa-trash"></i> Удалить
            </a>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>