<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include 'components/link.php'; ?>
<title></title>
</head>
<body>
<!--- Navbar --->
<?php include 'components/navbar.php'; ?>
<!--- End navbar --->
<?php
if ( isset( $_COOKIE[ 'user_id' ] ) ) {
  $user_id = $_COOKIE[ 'user_id' ];
} else {
  setcookie( 'user_id', create_unique_id(), time() + 60 * 60 * 24 * 30 );
}

if ( isset( $_POST[ 'update_cart' ] ) ) {
  $cart_id = $_POST[ 'cart_id' ];
  $qty = $_POST[ 'qty' ];

  $update_qty = $con->prepare( "UPDATE `cart` SET qty =?  WHERE id = ?" );
  $update_qty->execute( [ $qty, $cart_id ] );
  $success_msg[] = "Cart quantity updated!";
}

if ( isset( $_POST[ 'delete_item' ] ) ) {
  $cart_id = $_POST[ 'cart_id' ];
  $verify_delete_item = $con->prepare( "SELECT * FROM `cart` WHERE id = ?" );
  $verify_delete_item->execute( [ $cart_id ] );

  if ( $verify_delete_item->rowCount() > 0 ) {
    $delete_cart_id = $con->prepare( "DELETE FROM `cart` WHERE id = ?" );
    $delete_cart_id->execute( [ $cart_id ] );
    $success_msg[] = "Cart item deleted!";

  } else {
    $warning_msg[] = "Cart item already deleted!";
  }


}

if ( isset( $_POST[ 'empty_cart' ] ) ) {
  $verify_empty_cart = $con->prepare( "SELECT * FROM `cart` WHERE user_id =?" );
  $verify_empty_cart->execute( [ $user_id ] );

  if ( $verify_empty_cart->rowCount() > 0 ) {
    $delete_cart_id = $con->prepare( "DELETE FROM `cart` WHERE user_id =?" );
    $delete_cart_id->execute( [ $user_id ] );
    $success_msg[] = "Cart emptied!";
  } else {
    $warning_msg[] = "Cart already emptied!";
  }
}


?>
<div class="container mt-3">
<div class="card">
  <div class="card-body">
    <h3>Shopping cart</h3>
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php

        $grand_total = 0;
        $select_cart = $con->prepare( "SELECT * FROM `cart` WHERE user_id = ?" );
        $select_cart->execute( [ $user_id ] );
        if ( $select_cart->rowCount() > 0 ) {
          while ( $fetch_cart = $select_cart->fetch( PDO::FETCH_ASSOC ) ) {
            $select_products = $con->prepare( "SELECT * FROM `products` WHERE id = ?" );
            $select_products->execute( [ $fetch_cart[ 'product_id' ] ] );
            $fetch_product = $select_products->fetch( PDO::FETCH_ASSOC );
            ?>
      <form action="" method="POST">
        <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
        <tr>
          <td><img src="<?php echo $fetch_product['image'];?> " width="100" height="100"></td>
          <td><?= $fetch_cart['price']; ?></td>
          <td><input type="number" name="qty" required min="1" value="<?= $fetch_cart['qty']; ?>" max="99" maxlength="2" class="qty btn rounded-pill border border-warning"></td>
          <td><?php echo $sub_total = $fetch_cart['qty'] * $fetch_cart['price'] ;?></td>
          <td><button type="submit" class="btn btn-warning rounded-pill" name="update_cart">Update</button>
            <input type="submit" name="delete_item" value="Delete" class="btn btn-danger rounded-pill" ></td>
        </tr>
      </form>
      <?php
      $grand_total += $sub_total;
      }
      }
      else {
        echo "<p>product not found!</p>";
      }
      ?>
      </tbody>
      
      <div>
        <form action="" method="POST">
          <input type="submit" value="Empty Cart" name="empty_cart" class="btn-danger">
        </form>
      </div>
    </table>
    <div class="float-end">
      <h3>Grand total: <?php echo $grand_total;?></h3>
    </div>
  </div>
</div>
  <div class="mt-3 float-end">
  <a href="index.php" class="btn btn-primary">Continue Shopping</a> 
  <a href="checkout.php" class="btn btn-success">Checkout</a> 
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'components/alert.php'; ?>
</body>
</html>