<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
  echo json_encode(["status" => "error", "message" => "User not logged in"]);
  exit;
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
  echo json_encode(["status" => "error", "message" => "Invalid product ID"]);
  exit;
}

try {
  // ✅ Check if product already exists in cart
  $check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
  $check->bind_param("ii", $user_id, $product_id);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    $update = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
    $update->bind_param("ii", $user_id, $product_id);
    $update->execute();
    echo json_encode(["status" => "success", "message" => "Added to cart"]);
  } else {
    $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $insert->bind_param("ii", $user_id, $product_id);
    $insert->execute();
    echo json_encode(["status" => "success", "message" => "Product added to cart"]);
  }
} catch (Exception $e) {
  echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>