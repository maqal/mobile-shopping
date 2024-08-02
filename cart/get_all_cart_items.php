<?php
include '../connection.php'; // Adjust the path to your database connection file

header('Content-Type: application/json; charset=utf-8');

$response = [];

try {
    $query = "
        SELECT 
            user.name AS user_name,
            products.name AS product_name,
            cart.quantity,
            products.price,
            (cart.quantity * products.price) AS total_price
        FROM cart
        JOIN user ON cart.user_id = user.id
        JOIN products ON cart.product_id = products.id
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $cartItems =  $stmt->get_result();

    echo json_encode([
        'success' => true,
        'data' => $cartItems
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
