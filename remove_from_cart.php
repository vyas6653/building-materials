<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$product_id = $data['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(["error" => "No product ID provided"]);
    exit;
}

// ✅ Decrease quantity by 1, or remove row if it hits 0
$check = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['quantity'] > 1) {
        $update = $conn->prepare("UPDATE cart SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ?");
        $update->bind_param("ii", $user_id, $product_id);
        $update->execute();
        echo json_encode(["success" => "Quantity decreased"]);
    } else {
        $delete = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $delete->bind_param("ii", $user_id, $product_id);
        $delete->execute();
        echo json_encode(["success" => "Product removed"]);
    }
} else {
    echo json_encode(["error" => "Item not found"]);
}
?>