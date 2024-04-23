<?php
session_start(); // Ensure session is started

include __DIR__ . '/../components/connect.php'; // Check if this path is correct

// Initialize total cart items count
$total_cart_items = 0;

// Check if user is logged in and retrieve user information
$isLoggedIn = isset($_SESSION['userlogin']);
$isAdmin = $isLoggedIn && ($_SESSION['userRole'] == 'Admin');
$userName = $isLoggedIn ? $_SESSION['userlogin'] : '';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
    $count_cart_items = $con->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $count_cart_items->execute([$user_id]);
    $total_cart_items = (string) $count_cart_items->rowCount();
}

?>

<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand"> 
            <img src="images/logo.jpg" height="55" width="150" alt="Logo"> 
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav">
                <a href="index.php" class="nav-item nav-link active">Home</a> 
                <!-- <a href="products.php" class="nav-item nav-link">Products</a> -->
                <a href="orders.php" class="nav-item nav-link">My Orders</a> 
                <?php if ($isAdmin): ?>
                    <a href="admin.php" class="nav-item nav-link">Admin Panel</a>
                <?php endif; ?>
            </div>
            <div class="d-flex ms-auto">
                <a class="nav-link" href="shopping_cart.php" style="font-size: 20px; color: red;">
                    <span class="fa-solid fa-cart-shopping"><?= $total_cart_items; ?></span>
                </a>
                <?php if ($isLoggedIn): ?>
                    <span class="navbar-text">
                        Welcome, <?= htmlspecialchars($userName) ?>
                    </span>
                    <a href="users/logout.php" class="nav-item nav-link">Logout</a>
                <?php else: ?>
                    <a href="users/login.php" class="nav-item nav-link">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>
