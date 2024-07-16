document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById("loginForm");
    const customerForm = document.getElementById("customerForm");
    const customersTable = document.getElementById("customersTable") ? document.getElementById("customersTable").querySelector("tbody") : null;
    const logoutButton = document.querySelector("aside a[href='#']"); // Se asume que el botón de logout es un enlace dentro del aside

    if (window.location.pathname.endsWith("index.html")) {
        // Login Page
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
                    alert(data.message);
                    if (data.success) {
                        window.location.href = "dashboard.php"; // Redirige a dashboard.php
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    } else if (window.location.pathname.endsWith("dashboard.php")) {
        // Dashboard Page
        const links = document.querySelectorAll("aside a");
        const contentSections = document.querySelectorAll(".content-section");

        links.forEach(link => {
            link.addEventListener("click", function(event) {
                event.preventDefault();

                links.forEach(link => link.classList.remove("active"));
                link.classList.add("active");

                const targetId = link.getAttribute("data-target");

                contentSections.forEach(section => {
                    if (section.id === targetId) {
                        section.classList.add("active");
                    } else {
                        section.classList.remove("active");
                    }
                });

                if (targetId === "customers") {
                    loadCustomers();
                }
            });
        });

        if (customerForm) {
            customerForm.addEventListener("submit", function(event) {
                event.preventDefault();
                const formData = new FormData(customerForm);

                fetch("crud.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        loadCustomers();
                        customerForm.reset();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        function loadCustomers() {
            fetch("crud.php?action=list")
                .then(response => response.json())
                .then(customers => {
                    customersTable.innerHTML = '';
                    customers.forEach(customer => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${customer.name}</td>
                            <td>${customer.email}</td>
                            <td>
                                <button onclick="editCustomer(${customer.id})">Edit</button>
                                <button onclick="deleteCustomer(${customer.id})">Delete</button>
                            </td>
                        `;
                        customersTable.appendChild(row);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        window.editCustomer = function(id) {
            fetch(`crud.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(customer => {
                    document.getElementById("customerId").value = customer.id;
                    document.getElementById("customerName").value = customer.name;
                    document.getElementById("customerEmail").value = customer.email;
                })
                .catch(error => console.error('Error:', error));
        };

        window.deleteCustomer = function(id) {
            if (confirm('Are you sure you want to delete this customer?')) {
                fetch(`crud.php?action=delete&id=${id}`, {
                    method: "POST"
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        loadCustomers();
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        };

        // Handle logout
        if (logoutButton) {
            logoutButton.addEventListener("click", function(event) {
                event.preventDefault(); // Evita la acción predeterminada del enlace

                fetch('logout.php', { // Asegúrate de que la URL sea correcta
                    method: 'POST',
                    credentials: 'same-origin' // Incluye cookies para la sesión
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = 'index.html'; // Redirige al inicio después de logout
                    } else {
                        console.error('Logout failed');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validatePassword(password) {
        return password.length >= 6;
    }
});
