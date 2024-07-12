document.addEventListener("DOMContentLoaded", function() {
    const links = document.querySelectorAll(".menu-link");
    const sections = document.querySelectorAll(".content-section");
    const customerForm = document.getElementById("customerForm");
    const customersTable = document.getElementById("customersTable").querySelector("tbody");

    links.forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault();

            links.forEach(link => link.classList.remove("active"));
            link.classList.add("active");

            const target = link.getAttribute("data-target");

            sections.forEach(section => {
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

    customersTable.addEventListener("click", function(event) {
        if (event.target.classList.contains("edit")) {
            const id = event.target.dataset.id;
            fetch(`crud.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("customerId").value = data.id;
                    document.getElementById("customerName").value = data.name;
                    document.getElementById("customerEmail").value = data.email;
                })
                .catch(error => console.error('Error:', error));
        } else if (event.target.classList.contains("delete")) {
            const id = event.target.dataset.id;
            if (confirm("Are you sure you want to delete this customer?")) {
                fetch(`crud.php?action=delete&id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        loadCustomers();
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    });

    function loadCustomers() {
        fetch("crud.php?action=list")
            .then(response => response.json())
            .then(data => {
                customersTable.innerHTML = "";
                data.forEach(customer => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${customer.name}</td>
                        <td>${customer.email}</td>
                        <td>
                            <button class="edit" data-id="${customer.id}">Edit</button>
                            <button class="delete" data-id="${customer.id}">Delete</button>
                        </td>
                    `;
                    customersTable.appendChild(row);
                });
            })
            .catch(error => console.error('Error:', error));
    }
});
