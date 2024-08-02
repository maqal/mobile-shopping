<?php
header('Content-Type: application/json');

include('../connection.php');

$response = array('success' => false, 'message' => '');

if (!isset($_POST['productId'])) {
    $response['message'] = 'Product ID is required';
    echo json_encode($response);
    exit();
}

$productId = $_POST['productId'];
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$brand = $_POST['brand'];
$image_url = $_POST['original_image_url']; // Default to original image URL

if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == UPLOAD_ERR_OK) {
    $image_name = basename($_FILES["image_url"]["name"]);
    $target_dir = "../product/uploads/";
    $target_file = $target_dir . $image_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["image_url"]["tmp_name"]);
    if ($check === false) {
        $response['message'] = 'File is not an image';
        echo json_encode($response);
        exit();
    }

    if ($_FILES["image_url"]["size"] > 5000000) {
        $response['message'] = 'Sorry, your file is too large.';
        echo json_encode($response);
        exit();
    }

    if (!in_array($imageFileType, array("jpg", "jpeg", "png", "gif"))) {
        $response['message'] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
        echo json_encode($response);
        exit();
    }

    if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file)) {
        // $image_url = basename($_FILES["image_url"]["name"]);
        $image_url = $target_file;
    } else {
        $response['message'] = 'Sorry, there was an error uploading your file.';
        echo json_encode($response);
        exit();
    }
}

$sql = "UPDATE products SET name=?, description=?, price=?, brand=?, image_url=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdsdi", $name, $description, $price, $brand, $target_file, $productId);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Product updated successfully';
} else {
    $response['message'] = 'Failed to update product';
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
