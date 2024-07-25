document.addEventListener("DOMContentLoaded", function() {
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
            }, 1000); // 2000 ms = 2 segundos
        }
    }

    // Manejar el formulario de inicio de sesión
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            event.preventDefault();
            const email = document.getElementById("loginEmail").value;
            const password = document.getElementById("loginPassword").value;

            if (!validateEmail(email) || !validatePassword(password)) {
                alert("Invalid email or password format");
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "login.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showMessage(response.message, "dashboard.php");
                    } else {
                        showMessage(response.message);
                    }
                }
            };
            xhr.send(`email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`);
        });
    }

    // Validar email
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Validar password
    function validatePassword(password) {
        return password.length >= 6; // Ejemplo: contraseña debe tener al menos 6 caracteres
    }

    // Función para abrir el modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "block";
        }
    }

    // Función para cerrar el modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "none";
        }
    }

    // Abrir el modal de agregar cliente
    const addCustomerBtn = document.getElementById("addCustomerBtn");
    if (addCustomerBtn) {
        addCustomerBtn.addEventListener("click", function(event) {
            event.preventDefault();
            openModal("addCustomerModal");
        });
    }

    // Manejar el cierre del modal
    const closeButtons = document.querySelectorAll(".close");
    closeButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            const modal = button.closest(".modal");
            if (modal) {
                modal.style.display = "none";
            }
        });
    });

    // Cargar clientes desde el servidor
    function loadCustomers() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "get_customers.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const customers = JSON.parse(xhr.responseText);
                const tbody = document.querySelector("#customers tbody");
                tbody.innerHTML = ""; // Limpiar tabla existente

                customers.forEach(function(customer) {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${customer.id}</td>
                        <td>${customer.name}</td>
                        <td>${customer.email}</td>
                        <td>
                            <a href="#" class="edit-button" data-id="${customer.id}" data-name="${customer.name}" data-email="${customer.email}">Editar</a>
                            <a href="#" class="delete-button" data-id="${customer.id}">Eliminar</a>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                // Agregar eventos para los botones de editar
                const editButtons = document.querySelectorAll(".edit-button");
                editButtons.forEach(function(button) {
                    button.addEventListener("click", function(event) {
                        event.preventDefault();
                        const customerId = button.getAttribute("data-id");
                        const customerName = button.getAttribute("data-name");
                        const customerEmail = button.getAttribute("data-email");
                        openEditModal(customerId, customerName, customerEmail);
                    });
                });

                // Agregar eventos para los botones de eliminar
                const deleteButtons = document.querySelectorAll(".delete-button");
                deleteButtons.forEach(function(button) {
                    button.addEventListener("click", function(event) {
                        event.preventDefault();
                        const customerId = button.getAttribute("data-id");
                        if (confirm("Are you sure you want to delete this customer?")) {
                            deleteCustomer(customerId);
                        }
                    });
                });
            }
        };
        xhr.send();
    }

    // Abrir el modal de edición con datos del cliente
    function openEditModal(id, name, email) {
        const modal = document.getElementById("editCustomerModal");
        if (modal) {
            modal.style.display = "block";
            document.getElementById("editCustomerId").value = id;
            document.getElementById("editName").value = name;
            document.getElementById("editEmail_proveedor").value = email;
        }
    }

    // Eliminar cliente
    function deleteCustomer(customerId) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_customer.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    showMessage(response.message);
                    loadCustomers(); // Recargar la lista de clientes
                } else {
                    showMessage(response.message);
                }
            }
        };
        xhr.send(`id=${encodeURIComponent(customerId)}`);
    }

    // Cargar clientes al cargar la página
    loadCustomers();

    // Manejar el logout
    const logoutButton = document.getElementById("logoutButton");
    if (logoutButton) {
        logoutButton.addEventListener("click", function(event) {
            event.preventDefault();
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "logout.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showMessage(response.message, "index.php");
                    } else {
                        showMessage(response.message);
                    }
                }
            };
            xhr.send();
        });
    }

    // Cerrar los modales si se hace clic fuera de ellos
    window.addEventListener("click", function(event) {
        const modals = document.querySelectorAll(".modal");
        modals.forEach(function(modal) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });

    // Manejar el formulario de agregar cliente
    const addCustomerForm = document.getElementById("addCustomerForm");
    if (addCustomerForm) {
        addCustomerForm.addEventListener("submit", function(event) {
            event.preventDefault();
            const name = document.getElementById("name").value;
            const email = document.getElementById("email_proveedor").value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "add_customer.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showMessage(response.message);
                        closeModal("addCustomerModal");
                        loadCustomers(); // Recargar la lista de clientes
                    } else {
                        showMessage(response.message);
                    }
                }
            };
            xhr.send(`name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}`);
        });
    }

    // Manejar el formulario de editar cliente
    const editCustomerForm = document.getElementById("editCustomerForm");
    if (editCustomerForm) {
        editCustomerForm.addEventListener("submit", function(event) {
            event.preventDefault();
            const id = document.getElementById("editCustomerId").value;
            const name = document.getElementById("editName").value;
            const email = document.getElementById("editEmail_proveedor").value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "edit_customer.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showMessage(response.message);
                        closeModal("editCustomerModal");
                        loadCustomers(); // Recargar la lista de clientes
                    } else {
                        showMessage(response.message);
                    }
                }
            };
            xhr.send(`id=${encodeURIComponent(id)}&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}`);
        });
    }
});
