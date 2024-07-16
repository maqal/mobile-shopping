<?php

header('Content-Type: application/json; charset=utf-8'); // Set the content type to JSON

include '../connection.php';

$email = $_POST['email'];
$password = $_POST['password'];

$response = [];

try {
    // Use placeholders (?) in the SQL query
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    // Bind the actual values to the placeholders
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if ($password === $row['password']) {
                $_SESSION['userId'] = $row['id'];
                $_SESSION['userRole'] = $row['role'];
                $response['status'] = 'success';
                $response['userId'] = $row['id'];
                $response['role'] = $row['role'];
                $response['message'] = 'Login successful';
                // Here you can set session variables or handle the logged-in user
            } else {
                $response['status'] = 'error';
                $response['message'] = $row;
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Email not found';
        }
    } else {
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
    error_log($e->getMessage());
}

echo json_encode($response); // Return response as JSON
?>
