<?php
require_once '../../config.php';

header('Content-Type: application/json');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $leave_type = $_POST['leave_type'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $reason = $_POST['reason_for_leave'] ?? '';

    if (!$user_id || !$leave_type || !$start_date || !$end_date || !$reason) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO leave_requests (user_id, leave_type, start_date, end_date, reason_for_leave, request_timestamp, status) VALUES (?, ?, ?, ?, ?, NOW(), 'pending')");
    $stmt->bind_param("issss", $user_id, $leave_type, $start_date, $end_date, $reason);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
