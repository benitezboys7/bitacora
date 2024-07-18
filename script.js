document.addEventListener("DOMContentLoaded", function() {
    // Código previo...

    // Mostrar modal de edición con datos de cliente
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

    // Manejar la edición de un cliente
    const editCustomerForm = document.getElementById("editCustomerForm");
    editCustomerForm.addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(editCustomerForm);

        fetch("edit_customer.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                editCustomerModal.style.display = "none";
                window.location.href = "dashboard.php?view=customers"; // Recargar la vista de "Customers"
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Lógica para cargar datos de clientes
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
});
