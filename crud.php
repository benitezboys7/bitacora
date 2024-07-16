<?php
require 'database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

header('Content-Type: application/json');

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query('SELECT * FROM customers');
            echo json_encode($stmt->fetchAll());
            break;

        case 'get':
            $id = $_GET['id'] ?? '';
            if ($id) {
                $stmt = $pdo->prepare('SELECT * FROM customers WHERE id = ?');
                $stmt->execute([$id]);
                echo json_encode($stmt->fetch());
            } else {
                echo json_encode(['message' => 'ID is required']);
            }
            break;

        case 'delete':
            $id = $_GET['id'] ?? '';
            if ($id) {
                $stmt = $pdo->prepare('DELETE FROM customers WHERE id = ?');
                $stmt->execute([$id]);
                echo json_encode(['message' => 'Customer deleted successfully']);
            } else {
                echo json_encode(['message' => 'ID is required']);
            }
            break;
            
            case 'create':
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';
            
                if (empty($name) || empty($email)) {
                    echo json_encode(['message' => 'Name and email are required']);
                    break;
                }
            
                $stmt = $pdo->prepare('INSERT INTO customers (name, email) VALUES (?, ?)');
                $stmt->execute([$name, $email]);
                echo json_encode(['message' => 'Customer created successfully']);
                break;
            
        
            
        case 'update':
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';

            if (empty($name) || empty($email)) {
                echo json_encode(['message' => 'Name and email are required']);
                break;
            }

            if ($action === 'update' && $id) {
                $stmt = $pdo->prepare('UPDATE customers SET name = ?, email = ? WHERE id = ?');
                $stmt->execute([$name, $email, $id]);
                echo json_encode(['message' => 'Customer updated successfully']);
            } elseif ($action === 'create') {
                $stmt = $pdo->prepare('INSERT INTO customers (name, email) VALUES (?, ?)');
                $stmt->execute([$name, $email]);
                echo json_encode(['message' => 'Customer created successfully']);
            } else {
                echo json_encode(['message' => 'Invalid action or ID']);
            }
            break;

        default:
            echo json_encode(['message' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
}
?>
