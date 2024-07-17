document.addEventListener("DOMContentLoaded", function() {
    const links = document.querySelectorAll("aside a.menu-link");
    const contentSections = document.querySelectorAll(".content-section");

    links.forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault();

            // Desactivar todos los enlaces
            links.forEach(link => link.classList.remove("active"));

            // Activar el enlace actual
            this.classList.add("active");

            const targetId = this.getAttribute("data-target");

            // Ocultar todas las secciones de contenido
            contentSections.forEach(section => {
                section.classList.remove("active");
            });

            // Mostrar la sección de contenido objetivo
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.classList.add("active");
            }

            if (targetId === "customers") {
                loadCustomers();
            }
        });
    });

    function loadCustomers() {
        fetch('crud.php?action=list')
            .then(response => response.json())
            .then(customers => {
                const customersTable = document.querySelector("#customers table");
                if (customersTable) {
                    if (customers.length > 0) {
                        customersTable.innerHTML = customers.map(customer => `
                            <tr>
                                <td>${customer.name}</td>
                                <td>${customer.email}</td>
                                <td>
                                    <button onclick="editCustomer(${customer.id})">Edit</button>
                                    <button onclick="deleteCustomer(${customer.id})">Delete</button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        customersTable.innerHTML = '<tr><td colspan="3">No customers found</td></tr>';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Manejar logout
    const logoutButton = document.getElementById("logoutButton");
    if (logoutButton) {
        logoutButton.addEventListener("click", function(event) {
            event.preventDefault();

            fetch('logout.php', {
                method: 'POST',
                credentials: 'same-origin'
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = 'index.html';
                } else {
                    console.error('Logout failed');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
