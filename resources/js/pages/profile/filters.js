export function initProfileFilters() {
    const filtersRoot = document.querySelector('[data-profile-filters]');
    const resultsContainer = document.querySelector('[data-profile-results]');
    const paginationContainer = document.querySelector('[data-profile-pagination]');

    if (!filtersRoot || !resultsContainer || window.__profileFiltersInitialized) {
        return;
    }

    window.__profileFiltersInitialized = true;

    const courseSelect = filtersRoot.querySelector('[data-profile-course]');
    const subjectSelect = filtersRoot.querySelector('[data-profile-subject]');
    const typeCheckboxes = [...filtersRoot.querySelectorAll('[data-profile-type]')];

    const filterUrl = filtersRoot.dataset.filterUrl;
    const activeTab = filtersRoot.dataset.activeTab || 'resources';

    let abortController = null;

    const getSubjectsForCourse = (courseId) => {
        const courses = window.profileCoursesWithSubjects || [];
        const course = courses.find((item) => String(item.id) === String(courseId));

        return course?.subjects || [];
    };

    const resetSubjectSelect = () => {
        subjectSelect.innerHTML = '<option value="">Asignatura</option>';
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

    const buildUrl = (customUrl = null) => {
        const url = new URL(customUrl || filterUrl, window.location.origin);

        if (!customUrl && activeTab === 'favorites') {
            url.searchParams.set('tab', 'favorites');
        }

        if (!customUrl && courseSelect.value) {
            url.searchParams.set('course_id', courseSelect.value);
        }

        if (!customUrl && subjectSelect.value) {
            url.searchParams.set('subject_id', subjectSelect.value);
        }

        if (!customUrl) {
            typeCheckboxes
                .filter((checkbox) => checkbox.checked)
                .forEach((checkbox) => {
                    url.searchParams.append('types[]', checkbox.value);
                });
        }

        return url;
    };

    const setLoading = () => {
        resultsContainer.innerHTML = `
            <div class="profile-empty profile-loading">
                <h3>Cargando recursos...</h3>
                <p>Un momento.</p>
            </div>
        `;

        if (paginationContainer) {
            paginationContainer.innerHTML = '';
        }
    };

    const fetchProfileResources = async (customUrl = null) => {
        if (!filterUrl) {
            return;
        }

        if (abortController) {
            abortController.abort();
        }

        abortController = new AbortController();

        setLoading();

        try {
            const response = await fetch(buildUrl(customUrl).toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: abortController.signal,
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error('No se pudieron cargar los recursos.');
            }

            resultsContainer.innerHTML = data.html || '';

            if (paginationContainer) {
                paginationContainer.innerHTML = data.pagination_html || '';
            }
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            console.error(error);

            resultsContainer.innerHTML = `
                <div class="profile-empty">
                    <h3>No se pudieron cargar los recursos</h3>
                    <p>Prueba de nuevo en unos segundos.</p>
                </div>
            `;
        }
    };

    courseSelect.addEventListener('change', () => {
        populateSubjects();
        fetchProfileResources();
    });

    subjectSelect.addEventListener('change', () => {
        fetchProfileResources();
    });

    typeCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', () => {
            fetchProfileResources();
        });
    });

    if (paginationContainer) {
        paginationContainer.addEventListener('click', (event) => {
            const link = event.target.closest('a');

            if (!link) {
                return;
            }

            event.preventDefault();
            fetchProfileResources(link.href);
        });
    }
}