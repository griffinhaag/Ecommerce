<?php
include __DIR__ . '/../components/connect.php';
session_start();

if (isset($_POST['signup'])) {
    $email = $_POST['email'];
    $userName = $_POST['username'];
    $password = $_POST['pswd'];

    $options = ['cost' => 12];
    $hashedpas = password_hash($password, PASSWORD_BCRYPT, $options);

    $query = "SELECT * FROM users WHERE (username=? OR email=?)";
    $stmt = $con->prepare($query);
    $stmt->bindparam(1, $userName);
    $stmt->bindparam(2, $email);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        $query = "INSERT INTO users (username, email, pass, userRole) VALUES (:username, :email, :pass, 'User')";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':username', $userName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $hashedpas);
        if ($stmt->execute()) {
            $_SESSION['userlogin'] = $userName;
            $_SESSION['userRole'] = 'User';
            echo '<script>
                    alert("Registration successful. Redirecting to home page.");
                    setTimeout(function() {
                        window.location.href = "../index.php";
                    }, 2000);
                  </script>';
        } else {
            $error_msg[] = "Something went wrong. Please try again.";
        }
    } else {
        $warning_msg[] = "Username or email already exists.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
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
        <h2>Register Account</h2>
        <div class="card">
            <div class="card-header">Sign Up</div>
            <div class="card-body">
                <form action="#" method="POST">
                    <div class="mb-3 mt-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" required>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="username" class="form-label">User Name:</label>
                        <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="pwd" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="pwd" placeholder="Enter Password" name="pswd" required>
                    </div>
                    <div class="form-check mb-3">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="remember"> Remember me
                        </label>
                    </div>
                    <div class="d-grid gap-4">
                        <button type="submit" class="btn btn-primary" name='signup'>Register</button>
                        <a href="login.php" class="btn btn-primary">Login</a>
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
