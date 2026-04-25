// Toggle favorito en iconos de corazón
document.addEventListener('DOMContentLoaded', function () {
    const heartIcons = document.querySelectorAll('.icon-heart');

    // Path para corazón outline (vacío)
    const outlinePath = "m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z";

    // Path para corazón filled (lleno)
    const filledPath = "m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z";

    heartIcons.forEach(heart => {
        // Estado inicial: verificar si está marcado como favorito
        let isFavorite = heart.getAttribute('data-favorite') === 'true';
        const path = heart.querySelector('path');

        // Establecer estado inicial
        if (isFavorite) {
            path.setAttribute('d', filledPath);
        }

        heart.addEventListener('click', function () {
            if (isFavorite) {
                // Desmarcar favorito - cambiar a outline
                path.setAttribute('d', outlinePath);
                this.setAttribute('data-favorite', 'false');
                isFavorite = false;
            } else {
                // Marcar favorito - cambiar a filled morado oscuro
                path.setAttribute('d', filledPath);
                this.setAttribute('data-favorite', 'true');
                isFavorite = true;
            }
        });
    });

    // Toggle bookmark
    const bookmarkIcons = document.querySelectorAll('.icon-bookmark');

    bookmarkIcons.forEach(bookmark => {
        bookmark.addEventListener('click', function () {
            const isSaved = this.getAttribute('data-saved') === 'true';

            if (isSaved) {
                this.setAttribute('data-saved', 'false');
                this.setAttribute('fill', '#2d1b3d');
            } else {
                this.setAttribute('data-saved', 'true');
                this.setAttribute('fill', '#583473');
            }
        });
    });

    const tabContainer = document.querySelector('.forYou-follow-section');
    const forYouTab = document.querySelector('.k-forYou');
    const followTab = document.querySelector('.k-follow');

    if (tabContainer && forYouTab && followTab) {
        const setActiveTab = (tab) => {
            forYouTab.classList.remove('tab-active');
            followTab.classList.remove('tab-active');

            if (tab === 'follow') {
                followTab.classList.add('tab-active');
                tabContainer.classList.add('is-follow');
            } else {
                forYouTab.classList.add('tab-active');
                tabContainer.classList.remove('is-follow');
            }
        };

        forYouTab.addEventListener('click', () => setActiveTab('for-you'));
        followTab.addEventListener('click', () => setActiveTab('follow'));
    }

    // Follow button toggle
    const followButtons = document.querySelectorAll('.follow-btn, .teacher-follow-btn');

    followButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const span = this.querySelector('span');
            const isFollowing = span.textContent === 'Siguiendo';

            if (isFollowing) {
                span.textContent = 'Seguir';
                this.classList.remove('is-following');
            } else {
                span.textContent = 'Siguiendo';
                this.classList.add('is-following');
            }
        });
    });

    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileOverlay = document.querySelector('.mobile-overlay');
    const appLeft = document.querySelector('.app-left');

    if (mobileMenuBtn && mobileOverlay && appLeft) {
        const toggleMenu = () => {
            mobileMenuBtn.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            appLeft.classList.toggle('menu-open');
            
            // Prevent body scroll when menu is open
            if (appLeft.classList.contains('menu-open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        };

        mobileMenuBtn.addEventListener('click', toggleMenu);
        mobileOverlay.addEventListener('click', toggleMenu);
    }

    const previewModal = document.getElementById('resource-preview-modal');
    const previewStage = document.getElementById('resource-preview-stage');
    const previewTitle = document.getElementById('resource-preview-title');
    const previewTriggers = document.querySelectorAll('.resource-preview-trigger');

    if (previewModal && previewStage && previewTitle) {
        previewModal.hidden = true;

        const closePreview = () => {
            previewModal.hidden = true;
            previewStage.innerHTML = '';
            previewTitle.textContent = '';
            document.body.classList.remove('menu-open');
        };

        const openPreview = (url, kind, title) => {
            previewStage.innerHTML = '';
            previewTitle.textContent = title || 'Vista previa';

            if (kind === 'video') {
                const video = document.createElement('video');
                video.controls = true;
                video.preload = 'metadata';
                video.src = url;
                previewStage.appendChild(video);
            } else {
                const image = document.createElement('img');
                image.src = url;
                image.alt = title || 'Vista previa del recurso';
                previewStage.appendChild(image);
            }

            previewModal.hidden = false;
            document.body.classList.add('menu-open');
        };

        if (previewTriggers.length > 0) {
            previewTriggers.forEach(trigger => {
                trigger.addEventListener('click', () => {
                    const url = trigger.getAttribute('data-preview-url') || '';
                    const kind = trigger.getAttribute('data-preview-kind') || 'image';
                    const title = trigger.getAttribute('data-preview-title') || 'Vista previa';

                    if (!url) {
                        return;
                    }

                    openPreview(url, kind, title);
                });
            });
        }

        previewModal.querySelectorAll('[data-preview-close="true"]').forEach(closeEl => {
            closeEl.addEventListener('click', (event) => {
                event.preventDefault();
                closePreview();
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !previewModal.hidden) {
                closePreview();
            }
        });
    }

    const audioPlayers = document.querySelectorAll('[data-audio-player]');

    if (audioPlayers.length > 0) {
        const allAudio = Array.from(audioPlayers)
            .map(player => player.querySelector('[data-audio-el]'))
            .filter(Boolean);

        const formatTime = (seconds) => {
            if (!Number.isFinite(seconds) || seconds < 0) {
                return '0:00';
            }

            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${String(secs).padStart(2, '0')}`;
        };

        audioPlayers.forEach((player) => {
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
                    allAudio.forEach(other => {
                        if (other !== audio) {
                            other.pause();
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
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const courseSelect = document.getElementById('course-filter');
    const subjectSelect = document.getElementById('subject-filter');
    if (!courseSelect || !subjectSelect) {
        return;
    }

    const availableCourses = Array.isArray(window.coursesWithSubjects)
        ? window.coursesWithSubjects
        : [];

    function resetSubjects() {
        subjectSelect.innerHTML = '<option value="" selected disabled>Materia</option>';
        subjectSelect.disabled = true;
    }

    function loadSubjects(courseId) {
        const selectedCourse = availableCourses.find(course => course.id == courseId);

        subjectSelect.innerHTML = '<option value="" selected disabled>Materia</option>';

        if (!selectedCourse || !selectedCourse.subjects || selectedCourse.subjects.length === 0) {
            subjectSelect.disabled = true;
            return;
        }

        selectedCourse.subjects.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = subject.name;
            subjectSelect.appendChild(option);
        });

        subjectSelect.disabled = false;
    }

    resetSubjects();

    courseSelect.addEventListener('change', () => {
        const courseId = courseSelect.value;

        if (!courseId) {
            resetSubjects();
            return;
        }

        loadSubjects(courseId);
    });
});