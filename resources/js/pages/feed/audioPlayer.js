/**
 * Inicializa reproductores de audio personalizados y thumbnails de preview
 */
export function initAudioPlayers() {
    const allManagedAudio = new Set();

    const formatTime = (seconds) => {
        if (!Number.isFinite(seconds) || seconds < 0) {
            return '0:00';
        }

        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${String(secs).padStart(2, '0')}`;
    };

    const applyPreviewOrientation = (thumb, ratio) => {
        thumb.classList.remove('resource-preview-thumb--portrait', 'resource-preview-thumb--landscape', 'resource-preview-thumb--square');

        if (ratio < 0.85) {
            thumb.classList.add('resource-preview-thumb--portrait');
        } else if (ratio > 1.15) {
            thumb.classList.add('resource-preview-thumb--landscape');
        } else {
            thumb.classList.add('resource-preview-thumb--square');
        }
    };

    const initPreviewThumbs = (scope = document) => {
        const thumbs = scope.querySelectorAll('.resource-preview-trigger:not([data-preview-ready="true"])');

        thumbs.forEach((thumb) => {
            const kind = thumb.getAttribute('data-preview-kind');

            if (kind === 'image') {
                const image = thumb.querySelector('.resource-preview-media-image');

                if (image) {
                    const update = () => {
                        if (image.naturalWidth > 0 && image.naturalHeight > 0) {
                            applyPreviewOrientation(thumb, image.naturalWidth / image.naturalHeight);
                        }
                    };

                    if (image.complete) {
                        update();
                    } else {
                        image.addEventListener('load', update, { once: true });
                    }
                }
            }

            if (kind === 'video') {
                const video = thumb.querySelector('video');

                if (video) {
                    const update = () => {
                        if (video.videoWidth > 0 && video.videoHeight > 0) {
                            applyPreviewOrientation(thumb, video.videoWidth / video.videoHeight);
                        }
                    };

                    if (video.readyState >= 1) {
                        update();
                    } else {
                        video.addEventListener('loadedmetadata', update, { once: true });
                    }
                }
            }

            if (kind === 'pdf') {
                if (thumb.classList.contains('resource-preview-thumb')) {
                    applyPreviewOrientation(thumb, 0.75);
                }
            }

            thumb.setAttribute('data-preview-ready', 'true');
        });
    };

    const initAudioPlayersImpl = (scope = document) => {
        const players = [];

        if (scope.matches && scope.matches('[data-audio-player]:not([data-audio-ready="true"])')) {
            players.push(scope);
        }

        scope.querySelectorAll('[data-audio-player]:not([data-audio-ready="true"])').forEach((player) => {
            players.push(player);
        });

        players.forEach((player) => {
            const audio = player.querySelector('[data-audio-el]');
            const toggle = player.querySelector('[data-audio-toggle]');
            const icon = player.querySelector('[data-audio-icon]');
            const seek = player.querySelector('[data-audio-seek]');
            const volume = player.querySelector('[data-audio-volume]');
            const muteBtn = player.querySelector('[data-audio-mute]');
            const volumeIcon = player.querySelector('[data-audio-volume-icon]');
            const currentEl = player.querySelector('[data-audio-current]');
            const durationEl = player.querySelector('[data-audio-duration]');

            if (!audio || !toggle || !icon || !seek || !currentEl || !durationEl || !volume || !muteBtn || !volumeIcon) {
                return;
            }

            player.setAttribute('data-audio-ready', 'true');
            allManagedAudio.add(audio);

            let scrubbing = false;
            let lastVolume = 1;

            const updateProgress = () => {
                const duration = audio.duration || 0;
                const current = audio.currentTime || 0;
                const progress = duration > 0 ? (current / duration) * 100 : 0;

                seek.value = String(progress);
                seek.style.setProperty('--progress', `${progress}%`);
                currentEl.textContent = formatTime(current);
            };

            const setPausedState = (isPaused) => {
                icon.textContent = isPaused ? '▶' : '❚❚';
                toggle.setAttribute('aria-label', isPaused ? 'Reproducir audio' : 'Pausar audio');
            };

            const setVolumeUi = () => {
                const volumePercent = Math.round((audio.volume || 0) * 100);
                volume.value = String(volumePercent);
                volume.style.setProperty('--volume', `${volumePercent}%`);

                if (audio.muted || volumePercent === 0) {
                    volumeIcon.textContent = '🔇';
                    muteBtn.setAttribute('aria-label', 'Activar sonido');
                } else if (volumePercent < 45) {
                    volumeIcon.textContent = '🔉';
                    muteBtn.setAttribute('aria-label', 'Silenciar audio');
                } else {
                    volumeIcon.textContent = '🔊';
                    muteBtn.setAttribute('aria-label', 'Silenciar audio');
                }
            };

            audio.addEventListener('loadedmetadata', () => {
                durationEl.textContent = formatTime(audio.duration || 0);
                updateProgress();
            });

            audio.addEventListener('timeupdate', () => {
                if (!scrubbing) {
                    updateProgress();
                }
            });

            audio.addEventListener('ended', () => {
                setPausedState(true);
                audio.currentTime = 0;
                updateProgress();
            });

            toggle.addEventListener('click', () => {
                if (audio.paused) {
                    allManagedAudio.forEach((otherAudio) => {
                        if (otherAudio !== audio) {
                            otherAudio.pause();
                        }
                    });

                    audio.play().catch(() => {
                        setPausedState(true);
                    });
                    setPausedState(false);
                } else {
                    audio.pause();
                    setPausedState(true);
                }
            });

            seek.addEventListener('input', () => {
                scrubbing = true;
                const progress = Number(seek.value);
                seek.style.setProperty('--progress', `${progress}%`);
                const duration = audio.duration || 0;
                currentEl.textContent = formatTime((progress / 100) * duration);
            });

            seek.addEventListener('change', () => {
                const duration = audio.duration || 0;
                audio.currentTime = (Number(seek.value) / 100) * duration;
                scrubbing = false;
                updateProgress();
            });

            volume.addEventListener('input', () => {
                const nextVolume = Number(volume.value) / 100;
                audio.volume = Math.min(1, Math.max(0, nextVolume));
                audio.muted = nextVolume === 0;

                if (nextVolume > 0) {
                    lastVolume = nextVolume;
                }

                setVolumeUi();
            });

            muteBtn.addEventListener('click', () => {
                if (audio.muted || audio.volume === 0) {
                    audio.muted = false;
                    audio.volume = lastVolume > 0 ? lastVolume : 0.75;
                } else {
                    lastVolume = audio.volume;
                    audio.muted = true;
                }

                setVolumeUi();
            });

            audio.addEventListener('pause', () => setPausedState(true));
            audio.addEventListener('play', () => setPausedState(false));

            audio.volume = 0.8;
            setPausedState(true);
            updateProgress();
            setVolumeUi();
        });
    };

    const audioObserver = 'IntersectionObserver' in window
        ? new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                initAudioPlayersImpl(entry.target);
                observer.unobserve(entry.target);
            });
        }, {
            root: null,
            rootMargin: '180px 0px',
            threshold: 0.01,
        })
        : null;

    const observeAudioPlayers = (scope = document) => {
        const players = [];

        if (scope.matches && scope.matches('[data-audio-player]:not([data-audio-observed="true"])')) {
            players.push(scope);
        }

        scope.querySelectorAll('[data-audio-player]:not([data-audio-observed="true"])').forEach((player) => {
            players.push(player);
        });

        players.forEach((player) => {
            player.setAttribute('data-audio-observed', 'true');

            if (audioObserver) {
                audioObserver.observe(player);
            } else {
                initAudioPlayersImpl(player);
            }
        });
    };

    // Inicializar thumbnails y reproductores
    initPreviewThumbs();
    observeAudioPlayers();

    // Exportar funciones para cuando se carga más contenido
    window.__feedAudioPlayerUtils = {
        initPreviewThumbs,
        observeAudioPlayers
    };
}
