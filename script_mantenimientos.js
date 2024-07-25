document.addEventListener('DOMContentLoaded', function() {
    const addProductBtn = document.getElementById('addProductBtn');
    const productModal = document.getElementById('productModal');
    const closeModal = document.querySelector('#productModal .close');

    addProductBtn.addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Agregar Nuevo Producto';
        document.getElementById('productForm').reset();
        // Load providers and maintenance names
        loadProviders();
        loadMaintenanceNames();
        productModal.style.display = 'block';
    });

    closeModal.addEventListener('click', function() {
        productModal.style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target == productModal) {
            productModal.style.display = 'none';
        }
    };

    function loadProviders() {
        const providerSelect = document.getElementById('productProvider');
        fetch('get_providers.php') // Script to get providers
            .then(response => response.json())
            .then(data => {
                providerSelect.innerHTML = '<option value="">Seleccione un proveedor</option>';
                data.providers.forEach(provider => {
                    const option = document.createElement('option');
                    option.value = provider.id;
                    option.textContent = provider.name;
                    providerSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching providers:', error));
    }

    function loadMaintenanceNames() {
        const maintenanceSelect = document.getElementById('productName');
        fetch('get_maintenance_names.php') // Script to get maintenance names
            .then(response => response.json())
            .then(data => {
                maintenanceSelect.innerHTML = '<option value="">Seleccione un mantenimiento</option>';
                data.maintenance.forEach(maintenance => {
                    const option = document.createElement('option');
                    option.value = maintenance.id;
                    option.textContent = maintenance.name;
                    maintenanceSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching maintenance names:', error));
    }
});
