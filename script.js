document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");
    const logoutButton = document.getElementById("logoutButton");

    // Función para mostrar mensajes
    function showMessage(message, redirect = false) {
        const messageContainer = document.getElementById("messageContainer");
        if (messageContainer) {
            messageContainer.textContent = message;
            messageContainer.classList.remove("hidden");

            setTimeout(() => {
                messageContainer.classList.add("hidden");
                if (redirect) {
                    window.location.href = redirect;
                }
            }, 2000); // 2000 ms = 2 segundos
        }
    }

    // Manejar el formulario de inicio de sesión
    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            event.preventDefault();
            const email = document.getElementById("loginEmail").value;
            const password = document.getElementById("loginPassword").value;

            if (!validateEmail(email) || !validatePassword(password)) {
                alert("Invalid email or password format");
                return;
            }

            const formData = new FormData(loginForm);
            fetch("login.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message, data.success ? "dashboard.php" : false);
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Manejar el formulario de registro
    if (registerForm) {
        registerForm.addEventListener("submit", function(event) {
            event.preventDefault();
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            if (!validateEmail(email) || !validatePassword(password)) {
                alert("Invalid email or password format");
                return;
            }

            const formData = new FormData(registerForm);
            fetch("register.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(message => {
                showMessage(message, "/index.php"); // Redirige a la página de inicio de sesión
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Manejar el botón de cerrar sesión
    if (logoutButton) {
        logoutButton.addEventListener("click", function(event) {
            event.preventDefault();

            fetch("logout.php", {
                method: "POST"
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage("Logout exitoso!", "index.php"); // Redirige a la página de inicio
                } else {
                    console.error("Error en el logout: ", data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Mostrar modales
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "block";
        }
    }

    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "none";
        }
    }

    // Manejar el dashboard
    const links = document.querySelectorAll("aside a.menu-link");
    const contentSections = document.querySelectorAll(".content-section");

    if (links.length > 0 && contentSections.length > 0) {
        links.forEach(link => {
            link.addEventListener("click", function(event) {
                event.preventDefault();

                links.forEach(link => link.classList.remove("active"));
                this.classList.add("active");

                const target = this.getAttribute("data-target");

                contentSections.forEach(section => {
                    section.style.display = section.id === target ? "block" : "none";
                });

                // Cargar contenido para "Add Product"
                if (target === "add-product") {
                    fetch("register.php")
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById("addProductContent").innerHTML = html;
                    })
                    .catch(error => console.error('Error loading register.php:', error));
                }

                history.pushState(null, '', 'dashboard.php?view=' + target);
            });
        });

        // Mostrar la sección predeterminada o la sección indicada en el parámetro 'view'
        const defaultView = getParameterByName('view') || 'dashboard';
        document.getElementById(defaultView).style.display = 'block';
        document.querySelector(`aside a.menu-link[data-target=${defaultView}]`).classList.add('active');

        // Mostrar modal de añadir cliente
        const addCustomerBtn = document.getElementById("addCustomerBtn");
        const addCustomerModal = document.getElementById("addCustomerModal");
        const closeAddModal = addCustomerModal ? addCustomerModal.querySelector(".close") : null;

        if (addCustomerBtn) {
            addCustomerBtn.addEventListener("click", function(event) {
                event.preventDefault();
                showModal("addCustomerModal");
            });
        }

        if (closeAddModal) {
            closeAddModal.addEventListener("click", function() {
                hideModal("addCustomerModal");
            });
        }

        // Mostrar modal de editar cliente
        const editCustomerModal = document.getElementById("editCustomerModal");
        const editCustomerForm = document.getElementById("editCustomerForm");
        const closeEditModal = editCustomerModal ? editCustomerModal.querySelector(".close") : null;

        if (closeEditModal) {
            closeEditModal.addEventListener("click", function() {
                hideModal("editCustomerModal");
            });
        }

        // Enviar datos del formulario de edición mediante AJAX
        if (editCustomerForm) {
            editCustomerForm.addEventListener("submit", function(event) {
                event.preventDefault();

                const formData = new FormData(editCustomerForm);

                fetch("edit_customer.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Customer updated successfully");
                        hideModal("editCustomerModal");
                        loadCustomers(); // Recargar la lista de clientes
                        window.location.href = 'dashboard.php?view=customers';
                    } else {
                        alert("Error updating customer: " + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        // Cargar datos de clientes
        function loadCustomers() {
            const customersSection = document.getElementById("customers");
            if (customersSection) {
                fetch("get_customers.php")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const tbody = customersSection.querySelector("tbody");
                    if (tbody) {
                        tbody.innerHTML = ""; // Limpiar contenido previo
                        data.customers.forEach(customer => {
                            const tr = document.createElement("tr");
                            tr.innerHTML = `
                                <td>${customer.id}</td>
                                <td>${customer.name}</td>
                                <td>${customer.email}</td>
                                <td>
                                    <a href="#" class="edit-button" data-id="${customer.id}" data-name="${customer.name}" data-email="${customer.email}">Edit</a>
                                    <a href="delete_customer.php?id=${customer.id}" class="delete-button">Delete</a>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });

                        // Añadir evento a los nuevos botones de editar
                        document.querySelectorAll(".edit-button").forEach(button => {
                            button.addEventListener("click", function(event) {
                                event.preventDefault();

                                const customerId = this.dataset.id;
                                const customerName = this.dataset.name;
                                const customerEmail = this.dataset.email;

                                const editCustomerId = document.getElementById("editCustomerId");
                                const editName = document.getElementById("editName");
                                const editEmail = document.getElementById("editEmail");

                                if (editCustomerId && editName && editEmail) {
                                    editCustomerId.value = customerId;
                                    editName.value = customerName;
                                    editEmail.value = customerEmail;

                                    showModal("editCustomerModal");
                                }
                            });
                        });
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
            }
        }

        // Inicializar la carga de clientes al cargar la página
        loadCustomers();
    }

    function getParameterByName(name) {
        const url = new URL(window.location.href);
        return url.searchParams.get(name);
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validatePassword(password) {
        return password.length >= 6; // Ejemplo: longitud mínima de 6 caracteres
    }
});
