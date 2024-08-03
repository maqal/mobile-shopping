<?php
header('Content-Type: application/json; charset=utf-8');
include '../connection.php'; // Adjust the path to your database connection file

$response = [];

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

if ($result = $conn->query($query)) {
    $cartItems = [];

    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data' => $cartItems
    ]);

    // Free result set
    $result->free();
} else {
    echo json_encode([
        'success' => false,
        'message' => $conn->error
    ]);
}

// Close connection
$conn->close();
?>
