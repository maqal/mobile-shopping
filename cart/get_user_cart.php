<?php
session_start();
header('Content-Type: application/json; charset=utf-8'); 
include '../connection.php';

if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['userId'];

$query = "SELECT p.id AS product_id, p.name, p.price, p.image_url, c.quantity
          FROM cart c
          JOIN products p ON c.product_id = p.id
          WHERE c.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

echo json_encode(['success' => true, 'data' => $cartItems]);
?>
