<?php
require 'database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'list':
        $stmt = $pdo->query('SELECT * FROM customers');
        echo json_encode($stmt->fetchAll());
        break;

    case 'get':
        $id = $_GET['id'];
        $stmt = $pdo->prepare('SELECT * FROM customers WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
        break;

    case 'delete':
        $id = $_GET['id'];
        $stmt = $pdo->prepare('DELETE FROM customers WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['message' => 'Customer deleted successfully']);
        break;

    case 'create':
    case 'update':
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];

        if ($id) {
            $stmt = $pdo->prepare('UPDATE customers SET name = ?, email = ? WHERE id = ?');
            $stmt->execute([$name, $email, $id]);
            echo json_encode(['message' => 'Customer updated successfully']);
        } else {
            $stmt = $pdo->prepare('INSERT INTO customers (name, email) VALUES (?, ?)');
            $stmt->execute([$name, $email]);
            echo json_encode(['message' => 'Customer created successfully']);
        }
        break;
}
?>
