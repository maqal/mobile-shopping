<?php
header('Content-Type: application/json; charset=UTF-8');

$response = [];

// Assuming you have a function to connect to your database
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    // Collect input data
    $user_id = $_POST['userId'];
    $product_id = $_POST['productId'];
    $product_name = $_POST['productName'];
    $product_price = $_POST['productPrice'];
    $quantity = $_POST['quantity'];

    // Insert into cart table
    $query = "INSERT INTO cart (user_id, product_id, product_name, product_price, quantity) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisdi", $user_id, $product_id, $product_name, $product_price, $quantity);
   

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Product added to cart successfully';
    } 
    else {
        $response['message'] = 'Failed to add product to cart';
    }
    $stmt->close();
    $conn->close();
}
echo json_encode($response);
?>
