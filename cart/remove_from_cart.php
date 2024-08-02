<?php
session_start();
header('Content-Type: application/json; charset=utf-8'); 
include '../connection.php';

// Check if product_id is provided in the query parameters
if (!isset($_GET['product_id'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('success' => false, 'message' => 'Product ID is required'));
    exit;
}

// Sanitize and retrieve product_id from query parameters
$product_id = intval($_GET['product_id']);

// Perform SQL DELETE operation
$sql = "DELETE FROM cart WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$product_id]);
echo json_encode(array('success' => true, 'message' => 'Product removed from cart successfully'));

// Check if the deletion was successful
// if ($stmt->rowCount() > 0) {
//     echo json_encode(array('success' => true, 'message' => 'Product removed from cart successfully'));
// } else {
//     echo json_encode(array('success' => false, 'message' => 'Failed to remove product from cart'));
// }
?>
