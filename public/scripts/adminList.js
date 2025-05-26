document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#itemsTable tr');

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const switches = document.querySelectorAll('.disponibilidad-switch');

    switches.forEach(function (switchElement) {
        switchElement.addEventListener('change', function () {
            const itemId = this.getAttribute('data-id');
            const disponibilidad = this.checked ? 1 : 0;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/items/${itemId}/disponibilidad`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ disponibilidad: disponibilidad })
            })            
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al actualizar la disponibilidad.');
                }
                return response.json();
            })
            .then(data => {
                console.log('Disponibilidad actualizada correctamente.');
            })
            .catch(error => {
                this.checked = !this.checked;
                console.error('Error:', error);
            });
        });
    });
});
