<?php
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/');
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $id = create_unique_id();
    $qty = $_POST['qty'];

    $verify_cart = $con->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
    $verify_cart->execute([$user_id, $product_id]);

    $max_cart_items = $con->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $max_cart_items->execute([$user_id]);

    if ($verify_cart->rowCount() > 0) {
        $warning_msg[] = "Already added to cart";
    } elseif ($max_cart_items->rowCount() == 10) {
        $warning_msg[] = "Cart is full!";
    } else {
        $select_price = $con->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $select_price->execute([$product_id]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

        $insert_cart = $con->prepare("INSERT INTO `cart` (id, user_id, product_id, price, qty) VALUES (?, ?, ?, ?, ?)");
        $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);
        $success_msg[] = 'Added to cart!';
    }
}

$select_products = "SELECT id, name, price, description, image, category FROM products"; // Adjusted to include the category field
$stmt = $con->prepare($select_products);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="col">
            <div class="card product-card">
                <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="Product Image">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['name']; ?></h5>
                    <p class="card-text"><?php echo $row['description']; ?></p>
                    <p class="card-text"><strong>Category: </strong><?php echo $row['category']; ?></p> <!-- Display category -->
                    <div class="d-grid gap-2">
                        <form action="#" method="POST">
                            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                            <p class="price"><strong>$<?php echo $row['price']; ?></strong></p>
                            <input type="number" name="qty" value="1" min="1" max="99" class="form-control mb-2">
                            <button type="submit" name="add_to_cart" class="btn btn-warning btn-block">Add to Cart</button>
                            <a href="checkout.php" class="btn btn-danger btn-block">Buy Now</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo '<p class="empty">No products found!</p>';
}

?>
