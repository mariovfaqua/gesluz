document.addEventListener('DOMContentLoaded', function () {
    const toggleAddress = document.getElementById('toggleAddress');
    const editAddressBtn = document.getElementById('editAddressBtn');
    const submitBtn = document.getElementById('submitBtn');

    const addressIsEmpty = {{ empty(session('address')) ? 'true' : 'false' }};
    const isUserLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

    function handleAddressToggle() {
        if (toggleAddress.checked) {
            editAddressBtn.style.display = 'inline-block';

            if (addressIsEmpty) {
                submitBtn.disabled = true;
                submitBtn.classList.remove('btn-warning');
                submitBtn.classList.add('btn-secondary');
                submitBtn.innerText = 'Debes completar la dirección';
            } else {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-warning');
                submitBtn.innerText = 'Finalizar pedido';
            }
        } else {
            editAddressBtn.style.display = 'none';
            submitBtn.disabled = !isUserLoggedIn;
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-warning');
            submitBtn.innerText = 'Finalizar pedido';
        }
    }

    if (toggleAddress) {
        toggleAddress.addEventListener('change', handleAddressToggle);
        handleAddressToggle(); // Ejecutar al cargar
    }
});