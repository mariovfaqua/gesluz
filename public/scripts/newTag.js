document.addEventListener('DOMContentLoaded', function () {
    const addTagButton = document.getElementById('add-tag-button');
    const newTagInput = document.getElementById('new-tag');
    const tagsContainer = document.getElementById('tags-container');

    addTagButton.addEventListener('click', function () {
        if (newTagInput.classList.contains('d-none')) {
            // Mostrar el input si está oculto
            newTagInput.classList.remove('d-none');
            newTagInput.focus();
            addTagButton.classList.remove('btn-primary');
            addTagButton.classList.add('btn-secondary');

        } else if (newTagInput.value.trim() !== "") {
            // Si hay texto en el input, añadir el tag
            const newTagName = newTagInput.value.trim();
            const newTagId = `new_tag_${Date.now()}`;

            let newTagHTML = `
                <div class="col-12 col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" checked type="checkbox" id="${newTagId}" name="newTags[]" value="${newTagName}">
                        <label class="form-check-label" for="${newTagId}">${newTagName}</label>
                    </div>
                </div>
            `;

            tagsContainer.insertAdjacentHTML('beforeend', newTagHTML);

            // Limpiar el input y reiniciar el botón
            newTagInput.value = "";
            addTagButton.classList.remove('btn-primary');
            addTagButton.classList.add('btn-secondary');
        }
    });

    // Evento para cambiar el estilo del botón dependiendo del texto en el input
    newTagInput.addEventListener('input', function () {
        if (newTagInput.value.trim() !== "") {
            addTagButton.classList.remove('btn-secondary');
            addTagButton.classList.add('btn-primary');
        } else {
            addTagButton.classList.remove('btn-primary');
            addTagButton.classList.add('btn-secondary');
        }
    });
});
