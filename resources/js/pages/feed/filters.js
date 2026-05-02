export function initFilters() {
    const filtersRoot = document.querySelector('[data-feed-filters]');
    const resultsContainer = document.querySelector('[data-feed-results]');
    const loadMoreArea = document.querySelector('[data-feed-load-more-area]');

    if (!filtersRoot || !resultsContainer || window.__feedFiltersInitialized) {
        return;
    }

    window.__feedFiltersInitialized = true;

    const courseSelect = filtersRoot.querySelector('[data-filter-course]');
    const subjectSelect = filtersRoot.querySelector('[data-filter-subject]');
    const typeCheckboxes = [...filtersRoot.querySelectorAll('[data-filter-type]')];
    const searchInput = document.querySelector('[data-live-search]');

    const filterUrl = filtersRoot.dataset.filterUrl;
    const activeTab = filtersRoot.dataset.activeTab || 'for-you';

    let abortController = null;

    const reinitDynamicContent = (scope = resultsContainer) => {
        if (window.__feedAudioPlayerUtils) {
            window.__feedAudioPlayerUtils.initPreviewThumbs(scope);
            window.__feedAudioPlayerUtils.observeAudioPlayers(scope);
        }
    };

    const getSubjectsForCourse = (courseId) => {
        const courses = window.coursesWithSubjects || [];
        const course = courses.find((item) => String(item.id) === String(courseId));

        return course?.subjects || [];
    };

    const resetSubjectSelect = () => {
        subjectSelect.innerHTML = '<option value="" selected>Materia</option>';
        subjectSelect.value = '';
        subjectSelect.disabled = true;
    };

    const populateSubjects = () => {
        const courseId = courseSelect.value;

        resetSubjectSelect();

        if (!courseId) {
            return;
        }

        const subjects = getSubjectsForCourse(courseId);

        subjects.forEach((subject) => {
            const option = document.createElement('option');

            option.value = subject.id;
            option.textContent = subject.name;

            subjectSelect.appendChild(option);
        });

        subjectSelect.disabled = subjects.length === 0;
    };

    const buildUrl = () => {
        const url = new URL(filterUrl, window.location.origin);

        if (activeTab === 'following') {
            url.searchParams.set('tab', 'following');
        }

        if (courseSelect.value) {
            url.searchParams.set('course', courseSelect.value);
        }

        if (subjectSelect.value) {
            url.searchParams.set('subject', subjectSelect.value);
        }

        typeCheckboxes
            .filter((checkbox) => checkbox.checked)
            .forEach((checkbox) => {
                url.searchParams.append('types[]', checkbox.value);
            });

        return url;
    };

    const setLoading = () => {
        resultsContainer.innerHTML = `
            <div class="feed-search-loading">
                Cargando recursos...
            </div>
        `;

        if (loadMoreArea) {
            loadMoreArea.hidden = true;
        }
    };

    const fetchFilteredResources = async () => {
        if (!filterUrl) {
            return;
        }

        if (searchInput && searchInput.value.trim() !== '') {
            searchInput.value = '';
        }

        if (abortController) {
            abortController.abort();
        }

        abortController = new AbortController();

        setLoading();

        try {
            const response = await fetch(buildUrl().toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: abortController.signal,
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error('No se pudieron cargar los recursos.');
            }

            resultsContainer.innerHTML = data.html || '';
            reinitDynamicContent(resultsContainer);

            if (loadMoreArea) {
                loadMoreArea.innerHTML = data.load_more_html || '';
                loadMoreArea.hidden = false;
            }
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            console.error(error);

            resultsContainer.innerHTML = `
                <div class="feed-search-empty">
                    No se pudieron cargar los recursos filtrados.
                </div>
            `;

            if (loadMoreArea) {
                loadMoreArea.hidden = true;
            }
        }
    };

    courseSelect.addEventListener('change', () => {
        populateSubjects();
        fetchFilteredResources();
    });

    subjectSelect.addEventListener('change', fetchFilteredResources);

    typeCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', fetchFilteredResources);
    });
}