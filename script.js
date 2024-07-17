document.addEventListener("DOMContentLoaded", function() {
    const links = document.querySelectorAll("aside a.menu-link");
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
        fetch('php/customers.php')
            .then(response => response.text())
            .then(data => {
                const customersSection = document.querySelector("#customers");
                if (customersSection) {
                    customersSection.innerHTML = data;
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Handle logout
    const logoutButton = document.getElementById("logoutButton");
    if (logoutButton) {
        logoutButton.addEventListener("click", function(event) {
            event.preventDefault();

            fetch('php/logout.php', {
                method: 'POST',
                credentials: 'same-origin'
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = 'index.php';
                } else {
                    console.error('Logout failed');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Inactivity timer
    let inactivityTime = function () {
        let time;
        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;

        function logout() {
            window.location.href = 'index.php';
        }

        function resetTimer() {
            clearTimeout(time);
            time = setTimeout(logout, 1800000); // 30 minutos
        }
    };

    inactivityTime();
});
