document.addEventListener('DOMContentLoaded', () => {
    const roleToggle = document.getElementById('role-toggle');
    const roleInput = document.getElementById('role-input');
    const teacherFields = document.getElementById('teacher-fields');
    const roleIndicator = roleToggle?.querySelector('.k-role-indicator');

    if (!roleToggle || !roleInput || !teacherFields || !roleIndicator) {
        return;
    }

    const roleButtons = Array.from(roleToggle.querySelectorAll('.k-role-option'));
    const teacherInputs = teacherFields.querySelectorAll('input, select, textarea');

    const updateIndicator = (activeButton) => {
        if (!activeButton) {
            return;
        }

        roleIndicator.style.width = `${activeButton.offsetWidth}px`;
        roleIndicator.style.transform = `translateX(${activeButton.offsetLeft}px)`;
    };

    const updateTeacherFields = (isTeacher) => {
        teacherFields.classList.toggle('is-hidden', !isTeacher);

        teacherInputs.forEach((field) => {
            field.disabled = !isTeacher;
        });
    };

    const setActiveRole = (role) => {
        roleInput.value = role;
        let activeButton = null;

        roleButtons.forEach((button) => {
            const isActive = button.dataset.role === role;

            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-pressed', String(isActive));

            if (isActive) {
                activeButton = button;
            }
        });

        updateIndicator(activeButton || roleButtons[0]);
        updateTeacherFields(role === 'TEACHER');
    };

    roleButtons.forEach((button) => {
        button.addEventListener('click', () => {
            setActiveRole(button.dataset.role || 'TEACHER');
        });
    });

    setActiveRole(roleInput.value || 'TEACHER');

    requestAnimationFrame(() => {
        roleToggle.classList.add('is-ready');
    });

    window.addEventListener('resize', () => {
        const activeButton = roleToggle.querySelector('.k-role-option.is-active');
        updateIndicator(activeButton || roleButtons[0]);
    });
});