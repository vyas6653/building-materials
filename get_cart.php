<?php
session_start();
include 'db.php';
header("Content-Type: application/json");

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch only products for this user
    $sql = "SELECT p.id, p.name, p.price, p.image, c.quantity 
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }

    echo json_encode($cartItems);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>