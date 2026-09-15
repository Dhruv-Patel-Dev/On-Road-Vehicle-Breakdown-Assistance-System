<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json');

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] != 'service_provider'
) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$provider_id = $_SESSION['user_id'];

/*
 * Get all jobs assigned to this service provider
 */
$sql = "SELECT 
            sr.request_id,
            sr.service_type,
            sr.location,
            sr.status,
            sr.request_time,
            sr.completed_time,
            vo.name AS customer_name,
            vo.phone_number
        FROM service_requests sr
        JOIN vehicles v ON sr.vehicle_id = v.vehicle_id
        JOIN vehicle_owners vo ON v.owner_id = vo.id
        WHERE sr.provider_id = ?
        ORDER BY sr.request_time DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database prepare failed: ' . $conn->error
    ]);
    exit();
}

$stmt->bind_param("i", $provider_id);
$stmt->execute();

$result = $stmt->get_result();

$jobs = [];

while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}

$stmt->close();

/*
 * Keep the dashboard's "Jobs Completed Today" counter working
 */
$sql_count = "SELECT COUNT(*) AS jobs_today
              FROM service_requests
              WHERE provider_id = ?
              AND DATE(completed_time) = CURDATE()
              AND status = 'completed'";

$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("i", $provider_id);
$stmt_count->execute();

$count_result = $stmt_count->get_result();
$count_row = $count_result->fetch_assoc();

$jobs_today = $count_row['jobs_today'] ?? 0;

$stmt_count->close();
$conn->close();

echo json_encode([
    'jobs_today' => (int)$jobs_today,
    'jobs' => $jobs
]);
?>