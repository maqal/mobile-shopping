<?php
header('Content-Type: application/json; charset=utf-8'); // Set the content type to JSON

include '../connection.php';

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];

// Hash the password for security
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);

$response = [];

$sql = "INSERT INTO user (name, email, phone, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $phone, $password);

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'New record created successfully';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response); // Return response as JSON
?>
