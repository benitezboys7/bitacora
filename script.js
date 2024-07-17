document.addEventListener("DOMContentLoaded", function() {
    const logoutButton = document.getElementById("logoutButton");

    if (window.location.pathname.endsWith("dashboard.php")) {
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
                        if (targetId === "customers") {
                            loadCustomers();
                        }
                    } else {
                        section.classList.remove("active");
                    }
                });
            });
        });

        function loadCustomers() {
            fetch('list_customers.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(customers => {
                    const customersTable = document.querySelector("#customers table tbody");
                    if (customersTable) {
                        if (customers.length > 0) {
                            customersTable.innerHTML = customers.map(customer => `
                                <tr>
                                    <td>${customer.id}</td>
                                    <td>${customer.name}</td>
                                    <td>${customer.email}</td>
                                    <td>
                                        <button onclick="editCustomer(${customer.id})">Edit</button>
                                        <button onclick="deleteCustomer(${customer.id})">Delete</button>
                                    </td>
                                </tr>
                            `).join('');
                        } else {
                            customersTable.innerHTML = '<tr><td colspan="4">No customers found</td></tr>';
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        window.editCustomer = function(id) {
            fetch(`edit_customer.php?id=${id}`)
                .then(response => response.text())
                .then(html => {
                    const modal = document.createElement('div');
                    modal.classList.add('modal');
                    modal.innerHTML = html;
                    document.body.appendChild(modal);

                    const closeButton = modal.querySelector('.close');
                    closeButton.addEventListener('click', () => {
                        document.body.removeChild(modal);
                    });

                    window.onclick = function(event) {
                        if (event.target == modal) {
                            document.body.removeChild(modal);
                        }
                    };
                })
                .catch(error => console.error('Error:', error));
        };

        window.deleteCustomer = function(id) {
            if (confirm('Are you sure you want to delete this customer?')) {
                fetch(`delete_customer.php?id=${id}`, {
                    method: 'GET',
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (response.ok) {
                        loadCustomers(); // Recargar la lista después de eliminar
                    } else {
                        console.error('Delete failed');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        };

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

        inactivityTime();

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('view') === 'customers') {
            document.querySelector('aside a[data-target="customers"]').click();
        }
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
