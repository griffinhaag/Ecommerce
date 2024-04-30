<?php
session_start();
if (isset($_POST['login'])) {
    include __DIR__ . '/../components/connect.php';
    $userName = $_POST['username'];
    $password = $_POST['pswd'];

    $query = "SELECT username, email, pass, userRole FROM users WHERE username=:username OR email=:username";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':username', $userName, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($results) {
        $hashpass = $results['pass'];
        if (password_verify($password, $hashpass)) {
            $_SESSION['userlogin'] = $userName;
            $_SESSION['userRole'] = $results['userRole'];
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "../index.php";
                    }, 2000);
                  </script>';
        } else {
            $warning_msg[] = "Wrong password or username. Please try again!";
        }
    } else {
        $warning_msg[] = "User not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
<a href="../index.php" id="returnHomeBtn" class="btn">Return to Home</a>
    <div class="container mt-3 w-50">
        <h2>Login Page</h2>
        <div class="card">
            <div class="card-header">Login / Register</div>
            <div class="card-body">
                <?php if(!empty($warning_msg)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php foreach($warning_msg as $msg): echo $msg . '<br>'; endforeach; ?>
                    </div>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="mb-3 mt-3">
                        <label for="text" class="form-label">User Name / Email:</label>
                        <input type="text" class="form-control" id="username" placeholder="Enter Email / User Name" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="pwd" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="pwd" placeholder="Enter Password" name="pswd" required>
                    </div>
                    <div class="d-grid gap-4">
                        <button type="submit" class="btn btn-primary" name="login">Login</button>
                        <a href="register.php" class="btn btn-primary">Register</a>
                    </div>
                </form>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php include 'alert.php'; ?>
</body>
</html>
