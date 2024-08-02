<?php
header('Content-Type: application/json; charset=utf-8'); 
include '../connection.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['productName'];
    $description = $_POST['productDescription'];
    $price = $_POST['productPrice'];
    $brand = $_POST['productBrand'];
    $image_url = '';

    // Handle file upload
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['productImage'];
        $image_name = basename($image['name']);
        $target_dir = "../product/uploads/";
        $target_file = $target_dir . $image_name;

        // Ensure the uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            $image_url = $target_file;
        } else {
            $response['success'] = false;
            $response['message'] = 'Error uploading image';
            echo json_encode($response);
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, brand, image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $description, $price, $brand, $image_url);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['message'] = 'Error saving product: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
