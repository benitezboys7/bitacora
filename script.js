document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById("loginForm");
    const logoutButton = document.getElementById("logoutButton");

    if (window.location.pathname.endsWith("index.html") || window.location.pathname === "/") {
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
                    alert(data.message);
                    if (data.success) {
                        window.location.href = "dashboard.php"; // Redirige a dashboard.php
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

                const targetId = this.getAttribute("data-target");

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

        // Manejar el cierre de sesión
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

        // Manejar la inactividad del usuario
        inactivityTime();
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validatePassword(password) {
        return password.length >= 6;
    }

    function inactivityTime() {
        let time;
        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;

        function logout() {
            window.location.href = 'index.html';
        }

        function resetTimer() {
            clearTimeout(time);
            time = setTimeout(logout, 1800000); // 30 minutos
        }
    }
});
