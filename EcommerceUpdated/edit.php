<?php
session_start();
include __DIR__ . '/components/connect.php';

// Ensure there's an ID in the query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin.php');
    exit();
}

$product_id = $_GET['id'];

// Use a prepared statement to safely fetch the product details
$stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // Handle image upload if a new image is provided
    if (!empty($image)) {
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $new_image_name = "product_" . $product_id . '.' . $ext;
        $image_path = 'uploaded_files/' . $new_image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

        // Update query to include the image path
        $updateStmt = $con->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
        $updateStmt->execute([$name, $price, $description, $image_path, $product_id]);
    } else {
        // Update without changing the image
        $updateStmt = $con->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $updateStmt->execute([$name, $price, $description, $product_id]);
    }
    header('Location: admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #returnHomeBtn {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #155724;
            color: white;
        }
    </style>
</head>
<body>
<a href="index.php" id="returnHomeBtn" class="btn">Return to Home</a>
    <div class="container">
        <h1>Edit Product</h1>
        <form action="edit.php?id=<?= htmlspecialchars($product_id) ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required value="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Product Price</label>
                <input type="number" class="form-control" id="price" name="price" required value="<?= htmlspecialchars($product['price']) ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Product Description</label>
                <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="currentImage" class="form-label">Current Image</label>
                <div><img src="<?= $product['image'] ?>" alt="Current Image" style="width: 100px; height: auto;"></div>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Change Image</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
