<?php
header('Content-Type: application/json; charset=utf-8'); 
include '../connection.php'; 

$response = array();

$sql = "SELECT * FROM user";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

$conn->close();

echo json_encode($response);
?>
