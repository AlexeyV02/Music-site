<?php
require_once 'includes/config.php';

// Получаем все треки
$stmt = $pdo->query("
    SELECT t.*, u.name as added_by_name 
    FROM tracks t 
    LEFT JOIN users u ON t.added_by = u.id 
    ORDER BY t.added_at DESC
");
$tracks = $stmt->fetchAll();
?>

<?php require_once 'includes/header.php'; ?>

<h1 class="mb-4">Музыкальная библиотека</h1>

<!-- Кнопка перемешивания -->
<div class="mb-3">
    <button class="btn btn-outline-secondary" onclick="shufflePlaylist()">
        <i class="fas fa-random"></i> Перемешать плейлист
    </button>
</div>

<!-- Список треков -->
<div class="row" id="playlist">
    <?php foreach ($tracks as $track): ?>
        <div class="col-md-4 mb-4 track-item" data-id="<?= $track['id'] ?>">
            <div class="card h-100">
                <img src="<?= $track['cover_image'] ?: 'images/default-cover.jpg' ?>" 
                     class="card-img-top" 
                     alt="<?= htmlspecialchars($track['title']) ?>"
                     style="height: 200px; object-fit: cover;">
                
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($track['title']) ?></h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-user"></i> <?= htmlspecialchars($track['artist']) ?>
                    </p>
                    <p class="card-text small"><?= substr($track['description'] ?: 'Нет описания', 0, 100) ?>...</p>
                </div>
                
                <div class="card-footer bg-white">
                    <button class="btn btn-primary btn-sm play-btn" 
                            data-audio="<?= htmlspecialchars($track['audio_file']) ?>"
                            data-title="<?= htmlspecialchars($track['title']) ?>"
                            data-artist="<?= htmlspecialchars($track['artist']) ?>">
                        <i class="fas fa-play"></i> Слушать
                    </button>
                    
                    <a href="track.php?id=<?= $track['id'] ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-info-circle"></i> Подробнее
                    </a>
                    
                    <?php if (isAdmin()): ?>
                        <button class="btn btn-danger btn-sm float-end delete-track" 
                                data-id="<?= $track['id'] ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="js/playlist.js"></script>

<?php require_once 'includes/footer.php'; ?>