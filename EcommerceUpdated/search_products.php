<?php
include __DIR__ . '/components/connect.php';

$search = $_GET['search'] ?? '';

$products = $con->prepare("SELECT * FROM products WHERE name LIKE ?");
$products->execute(["%$search%"]);
$products = $products->fetchAll(PDO::FETCH_ASSOC);

$output = '<table class="table mt-3">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Product Name</th>
            <th scope="col">Image</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>';

foreach ($products as $product) {
    $imagePath = htmlspecialchars($product['image']); // Assuming 'image_path' is the column name in your database
    $output .= '<tr>
        <th scope="row">' . htmlspecialchars($product['id']) . '</th>
        <td>' . htmlspecialchars($product['name']) . '</td>
        <td><img src="' . $imagePath . '" style="width:100px;height:100px;"></td>
        <td>
            <a href="edit.php?id=' . $product['id'] . '" class="btn btn-primary">Edit</a>
            <a href="delete.php?id=' . $product['id'] . '" class="btn btn-danger">Delete</a>
        </td>
    </tr>';
}

$output .= '</tbody></table>';
echo $output;
