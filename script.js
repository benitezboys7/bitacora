document.addEventListener("DOMContentLoaded", function() {
    if (window.location.pathname.endsWith("index.html")) {
        // Login Page
        const loginForm = document.getElementById("loginForm");
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
                    window.location.href = "dashboard.html";
                }
            })
            .catch(error => console.error('Error:', error));
        });
    } else if (window.location.pathname.endsWith("register.html")) {
        // Register Page
        const registerForm = document.getElementById("registerForm");
        registerForm.addEventListener("submit", function(event) {
            event.preventDefault();
            const name = document.getElementById("registerName").value;
            const email = document.getElementById("registerEmail").value;
            const password = document.getElementById("registerPassword").value;

            if (!validateName(name) || !validateEmail(email) || !validatePassword(password)) {
                alert("Invalid input");
                return;
            }

            const formData = new FormData(registerForm);
            fetch("register.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    window.location.href = "index.html";
                }
            })
            .catch(error => console.error('Error:', error));
        });
    } else if (window.location.pathname.endsWith("dashboard.html")) {
        // Dashboard Page
        const links = document.querySelectorAll("aside a");
        const mainContent = document.querySelector("main");

        links.forEach(link => {
            link.addEventListener("click", function(event) {
                event.preventDefault();

                links.forEach(link => link.classList.remove("active"));
                link.classList.add("active");

                const target = link.querySelector("h3").innerText.toLowerCase();

                document.querySelectorAll(".content-section").forEach(section => {
                    if (section.id === target) {
                        section.classList.add("active");
                    } else {
                        section.classList.remove("active");
                    }
                });

                if (target === "customers") {
                    loadCustomers();
                }
            });
        });

        const customerForm = document.getElementById("customerForm");
        const customersTable = document.getElementById("customersTable").querySelector("tbody");

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
                loadCustomers();
                customerForm.reset();
            })
            .catch(error => console.error('Error:', error));
        });

        customersTable.addEventListener("click", function
