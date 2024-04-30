<?php
include_once('components/connect.php');

if (!isset($_COOKIE['user_id'])) {
    setcookie('user_id', create_unique_id(), time() + 60 * 60 * 24 * 30);
    $_COOKIE['user_id'] = create_unique_id(); // Assign a value for immediate use
}

if (isset($_POST['add'])) {
    $id = create_unique_id();
    $name = $_POST['pname'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $image = $_FILES['image']['name'];
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id().'.'.$ext;
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'uploaded_files/'.$rename;

    if ($image_size > 2000000) {
        $warning_msg[] = 'Image size is too large';
    } else {
        $query = "INSERT INTO products (id, name, price, image, description, category) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bindparam(1, $id);
        $stmt->bindparam(2, $name);
        $stmt->bindparam(3, $price);
        $stmt->bindparam(4, $image_folder);
        $stmt->bindparam(5, $description);  // Bind description parameter
        $stmt->execute();
        move_uploaded_file($image_tmp_name, $image_folder);
        $success_msg[] = 'Product added!';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include 'components/link.php'; ?>
	<style>
        #returnHomeBtn {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #155724;
            color: white;
        }
    </style>
    <title>Add Product</title>
</head>
<body>
<a href="index.php" id="returnHomeBtn" class="btn">Return to Home</a>
    <div class="container mt-5 mb-5 d-flex justify-content-center">
        <div class="card w-50">
            <div class="card-body">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <div class="mb-3 mt-3">
                        <label for="pname">Product Name</label>
                        <input type="text" class="form-control" name="pname" id="pname" required maxlength="50" placeholder="Enter product name">
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="price">Product Price</label>
                        <input type="number" class="form-control" name="price" id="price" required min="0" max="99999" placeholder="Enter product price">
                    </div>

                    <div class="mb-3">
                        <label for="description">Product Description</label>
                        <textarea class="form-control" name="description" id="description" required placeholder="Enter product description" maxlength="255"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="category">Product Category</label>
                        <select class="form-control" name="category" id="category" required>
                            <option value="Womens">Womens</option>
                            <option value="Mens">Mens</option>
                        </select>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" name="image" id="image">                   
                    </div>

                    <div class="d-grid gap-4">
                        <button type="submit" name="add" value="add" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>
