<?php
header('Content-Type: application/json; charset=utf-8'); 
include '../connection.php';

$sql = "SELECT * FROM products WHERE trending = 1";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);
$conn->close();

?>

