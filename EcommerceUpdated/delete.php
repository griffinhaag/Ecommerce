<?php
session_start();
include __DIR__ . '/components/connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin.php');
    exit();
}

$product_id = $_GET['id'];
$stmt = $con->prepare("DELETE FROM products WHERE id = ?");
if ($stmt->execute([$product_id])) {
    $_SESSION['message'] = "The product has been successfully deleted.";
}

header('Location: admin.php');
exit();
