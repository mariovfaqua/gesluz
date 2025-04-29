document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('toggleAddress');
    const fields = document.getElementById('addressFields');

    toggle.addEventListener('change', function() {
        if (toggle.checked) {
            fields.classList.add('show');
            // marca los nuevos campos como obligatorios
            document.getElementById('nombre').setAttribute('required', 'required');
            document.getElementById('linea_1').setAttribute('required', 'required');
            document.getElementById('pais').setAttribute('required', 'required');
            document.getElementById('provincia').setAttribute('required', 'required');
            document.getElementById('ciudad').setAttribute('required', 'required');
            document.getElementById('codigo_postal').setAttribute('required', 'required');
        } else {
            fields.classList.remove('show');
            // quitar la obligatoriedad
            ['nombre','linea_1','pais','provincia','ciudad','codigo_postal']
                .forEach(id => document.getElementById(id).removeAttribute('required'));
        }
    });
});