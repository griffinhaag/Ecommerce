<?php
session_start();
include __DIR__ . '/components/connect.php';

if (isset($_SESSION['userlogin']) && $_SESSION['userRole'] === 'Admin') {
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Dashboard</title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <a href="index.php" id="returnHomeBtn" class="btn">Return to Home</a> 
    
    <div class="container mt-5"> 
        <h1>Welcome, Admin!</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <script>
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 2000);
            </script>
        <?php 
            unset($_SESSION['message']);
        endif; 
        ?>
        <div class="d-flex justify-content-end">
            <input class="form-control me-2" type="search" placeholder="Search Product" aria-label="Search" id="productSearch" onkeyup="searchProducts()">
        </div>
        <div id="productsTable">
            
        </div>
        <a href="add_product.php" class="btn btn-success">Add Product</a>
    </div>

    <script>
    function searchProducts() {
        var input = $('#productSearch').val();
        $.ajax({
            url: 'search_products.php',
            type: 'GET',
            data: {search: input},
            success: function(data) {
                $('#productsTable').html(data);
            }
        });
    }
    searchProducts(); // Call on page load to display all products initially
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
} else {
    header('Location: index.php');
    exit();
}
?>
