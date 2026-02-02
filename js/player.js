document.addEventListener('DOMContentLoaded', function() {
    const audioPlayer = document.getElementById('audio-player');
    const nowPlaying = document.getElementById('now-playing');
    
    // Обработка кнопок "Слушать"
    document.querySelectorAll('.play-btn').forEach(button => {
        button.addEventListener('click', function() {
            const audioSrc = this.getAttribute('data-audio');
            const title = this.getAttribute('data-title');
            const artist = this.getAttribute('data-artist');
            
            audioPlayer.src = audioSrc;
            audioPlayer.play();
            
            nowPlaying.textContent = `Сейчас играет: ${artist} - ${title}`;
            
            // Добавляем визуальный эффект
            document.querySelectorAll('.play-btn').forEach(btn => {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-primary');
            });
            this.classList.remove('btn-primary');
            this.classList.add('btn-success');
        });
    });
    
    // Перемешивание плейлиста
    window.shufflePlaylist = function() {
        const container = document.getElementById('playlist');
        const items = Array.from(container.querySelectorAll('.track-item'));
        
        // Случайная перестановка
        for (let i = items.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            container.appendChild(items[j]);
        }
        
        // Показываем уведомление
        const alert = document.createElement('div');
        alert.className = 'alert alert-info alert-dismissible fade show';
        alert.innerHTML = `
            Плейлист перемешан!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        container.parentNode.insertBefore(alert, container);
        
        // Автоматически скрыть через 3 секунды
        setTimeout(() => {
            alert.remove();
        }, 3000);
    };
    
    // Удаление трека через AJAX (для админа)
    document.querySelectorAll('.delete-track').forEach(button => {
        button.addEventListener('click', function() {
            const trackId = this.getAttribute('data-id');
            
            if (confirm('Удалить этот трек?')) {
                fetch(`ajax/delete_track.php?id=${trackId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('.track-item').remove();
                        }
                    });
            }
        });
    });
});