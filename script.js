document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById("loginForm");
    const logoutButton = document.getElementById("logoutButton");

    function showMessage(message) {
        const messageContainer = document.getElementById("messageContainer");
        messageContainer.textContent = message;
        messageContainer.classList.remove("hidden");

        setTimeout(() => {
            messageContainer.classList.add("hidden");
            // En el caso de logout, redirige a la página deseada después de 1 segundo
            window.location.href = "dashboard.php";
        }, 1000); // 1000 ms = 1 segundos
    }

    if (window.location.pathname.endsWith("index.php") || window.location.pathname === "/") {
        // Página de inicio de sesión
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
                    showMessage(data.message);
                    if (data.success) {
                        setTimeout(() => {
                            window.location.href = "dashboard.php"; // Redirige a dashboard.php después de mostrar el mensaje
                        }, 2000);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    } else if (window.location.pathname.endsWith("dashboard.php")) {
        // Página del Dashboard
        const links = document.querySelectorAll("aside a.menu-link");
        const contentSections = document.querySelectorAll(".content-section");

        links.forEach(link => {
            link.addEventListener("click", function(event) {
                event.preventDefault();

                links.forEach(link => link.classList.remove("active"));
                this.classList.add("active");

                const target = this.getAttribute("data-target");

                contentSections.forEach(section => {
                    section.style.display = section.id === target ? "block" : "none";
                });

                history.pushState(null, '', 'dashboard.php?view=' + target);
            });
        });

        // Mostrar la sección predeterminada o la sección indicada en el parámetro 'view'
        const defaultView = getParameterByName('view') || 'dashboard';
        document.getElementById(defaultView).style.display = 'block';
        document.querySelector(`aside a.menu-link[data-target=${defaultView}]`).classList.add('active');

        // Botón de cerrar sesión
        if (logoutButton) {
            logoutButton.addEventListener("click", function(event) {
                event.preventDefault();

                fetch("logout.php", {
                    method: "POST"
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage("Logout exitoso!"); // Muestra el mensaje de logout exitoso
                    } else {
                        console.error("Error en el logout: ", data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        // Mostrar modal de añadir cliente
        const addCustomerBtn = document.getElementById("addCustomerBtn");
        const addCustomerModal = document.getElementById("addCustomerModal");
        const closeAddModal = addCustomerModal.querySelector(".close");

        if (addCustomerBtn) {
            addCustomerBtn.addEventListener("click", function(event) {
                event.preventDefault();
                addCustomerModal.style.display = "block";
            });
        }

        if (closeAddModal) {
            closeAddModal.addEventListener("click", function() {
                addCustomerModal.style.display = "none";
            });
        }

        // Mostrar modal de editar cliente
        const editCustomerModal = document.getElementById("editCustomerModal");
        const editCustomerForm = document.getElementById("editCustomerForm");
        const closeEditModal = editCustomerModal.querySelector(".close");

        if (closeEditModal) {
            closeEditModal.addEventListener("click", function() {
                editCustomerModal.style.display = "none";
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
                        editCustomerModal.style.display = "none";
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
            fetch("get_customers.php")
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const tbody = document.querySelector("#customers tbody");
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

                        document.getElementById("editCustomerId").value = customerId;
                        document.getElementById("editName").value = customerName;
                        document.getElementById("editEmail").value = customerEmail;

                        editCustomerModal.style.display = "block";
                    });
                });
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
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
        // Aquí puedes agregar validaciones adicionales para la contraseña si lo deseas
        return password.length >= 6; // Ejemplo: longitud mínima de 6 caracteres
    }
});
