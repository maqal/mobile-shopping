<?php
header('Content-Type: application/json; charset=utf-8'); 

require_once 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON data sent from the client-side JavaScript
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize data (example, you might want to validate product_id, product_name, etc.)

    // Sample validation (you should add more as per your requirements)
    $productId = isset($data['product_id']) ? intval($data['product_id']) : 0;
    $productName = isset($data['product_name']) ? $data['product_name'] : '';
    $productPrice = isset($data['product_price']) ? floatval($data['product_price']) : 0.0;
    $quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;

    // Insert into cart table (example, adjust as per your actual database schema)
    $stmt = $pdo->prepare("INSERT INTO cart (product_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");
    $stmt->execute([$productId, $productName, $productPrice, $quantity]);

    // Check if insertion was successful
    $affectedRows = $stmt->rowCount();
    if ($affectedRows > 0) {
        // Return success response to the client-side JavaScript
        $response = [
            'success' => true,
            'message' => 'Product added to cart successfully!'
        ];
    } else {
        // Return error response
        $response = [
            'success' => false,
            'message' => 'Failed to add product to cart.'
        ];
    }

    // Output JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Handle invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}
?>
