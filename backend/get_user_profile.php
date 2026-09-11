<?php

session_start();

include 'db_connect.php';

header('Content-Type: application/json');

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] != 'vehicle_owner'
) {
    http_response_code(401);

    echo json_encode([
        'error' => 'Unauthorized'
    ]);

    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT name, email, phone_number
        FROM vehicle_owners
        WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        'error' => 'Database prepare failed: ' . $conn->error
    ]);

    exit();
}

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    echo json_encode([
        'success' => true,
        'user' => $row
    ]);

} else {

    echo json_encode([
        'success' => false,
        'error' => 'User not found'
    ]);

}

$stmt->close();

$conn->close();

?>