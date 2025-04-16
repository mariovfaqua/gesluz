document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('toggleFilters');
    const filterForm = document.getElementById('filterForm');
    const activeFilters = document.getElementById('activeFilters');

    const searchInput = document.getElementById('searchTags');
    const tagItems = document.querySelectorAll('#tagsContainer .tag-item');

    toggleButton?.addEventListener('click', () => {
        const isHidden = filterForm.classList.contains('d-none');

        filterForm.classList.toggle('d-none', !isHidden);
        activeFilters.classList.toggle('d-none', isHidden);

        toggleButton.textContent = isHidden ? 'Ocultar filtros' : 'Editar filtros';
    });

    searchInput?.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();

        tagItems.forEach(item => {
            const label = item.querySelector('label').textContent.toLowerCase();
            item.style.display = label.includes(query) ? 'block' : 'none';
        });
    });
});
