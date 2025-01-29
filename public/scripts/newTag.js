document.addEventListener('DOMContentLoaded', function () {
    const addTagButton = document.getElementById('add-tag-button');
    const newTagInput = document.getElementById('new-tag');
    const tagsContainer = document.getElementById('tags-container');
    const errorMessage = document.getElementById('tag-error');

    addTagButton.addEventListener('click', function () {
        if (newTagInput.classList.contains('d-none')) {
            // Mostrar el input si está oculto
            newTagInput.classList.remove('d-none');
            newTagInput.focus();
            addTagButton.classList.remove('btn-primary');
            addTagButton.classList.add('btn-secondary');
            return;
        }

        const newTagName = newTagInput.value.trim().toLowerCase(); // Normalizar nombre (minúsculas)
        if (newTagName === "") return;

        // Comprobar si el tag ya existe
        let existingTags = Array.from(tagsContainer.querySelectorAll('.form-check-label')).map(label => label.textContent.trim().toLowerCase());

        if (existingTags.includes(newTagName)) {
            errorMessage.textContent = "*El tag ya existe.";
            errorMessage.classList.remove('d-none');
            return;
        }

        // Crear nuevo tag si no existe
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

        // Limpiar el input y ocultar el mensaje de error
        newTagInput.value = "";
        errorMessage.classList.add('d-none');
        addTagButton.classList.remove('btn-primary');
        addTagButton.classList.add('btn-secondary');
    });

    // Evento para cambiar el estilo del botón dependiendo del texto en el input
    newTagInput.addEventListener('input', function () {
        if (newTagInput.value.trim() !== "") {
            addTagButton.classList.remove('btn-secondary');
            addTagButton.classList.add('btn-primary');
            errorMessage.classList.add('d-none'); // Ocultar error al escribir
        } else {
            addTagButton.classList.remove('btn-primary');
            addTagButton.classList.add('btn-secondary');
        }
    });
});
