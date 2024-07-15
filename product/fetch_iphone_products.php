<?php
header('Content-Type: application/json; charset=utf-8');

require '../connection.php';

$brand = 'IPhone'; // Brand to filter

$sql = "SELECT name, description, price, brand, image_url FROM products WHERE brand = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $brand);
$stmt->execute();
$result = $stmt->get_result();

$products = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);

$stmt->close();
$conn->close();
?>
