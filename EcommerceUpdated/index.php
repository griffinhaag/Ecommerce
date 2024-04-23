
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

  <!--- product-card --->
  <div class="container py-4">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">


    <?php include 'components/product_card.php'; ?>
     
   
    </div>
  </div>

  <!--- End product-card --->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>
<?php include 'components/alert.php'; ?>
</body>
</html>