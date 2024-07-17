<?php
// customers.php
header('Content-Type: text/html');

$customers = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
    ['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com'],
    // Agrega más clientes según sea necesario
];

echo '<table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>';

foreach ($customers as $customer) {
    echo '<tr>
            <td>' . htmlspecialchars($customer['name']) . '</td>
            <td>' . htmlspecialchars($customer['email']) . '</td>
            <td>
                <button onclick="editCustomer(' . $customer['id'] . ')">Edit</button>
                <button onclick="deleteCustomer(' . $customer['id'] . ')">Delete</button>
            </td>
        </tr>';
}

echo '  </tbody>
    </table>';
?>
