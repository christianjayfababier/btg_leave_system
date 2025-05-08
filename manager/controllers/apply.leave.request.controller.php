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

    // Calculate duration in days   
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $interval = $start->diff($end);
    $days = $interval->days + 1; // +1 to include both start and end dates

    if ($start > $end) {
        echo json_encode(['status' => 'error', 'message' => 'Start date cannot be after end date.']);
        exit;
    }

    // Get current balance
    $stmt = $conn->prepare("SELECT leave_balance FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($current_balance);
    $stmt->fetch();
    $stmt->close();

    // Check if balance is sufficient
    if ($current_balance === null) {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        exit;
    }

    if ($current_balance < $days) {
        echo json_encode([
            'status' => 'error',
            'message' => 'You currently have no remaining leave days. Please review your leave balance or contact admin for further assistance.'
        ]);
        exit;
    }



    $conn->begin_transaction();

    try {
        // Insert the leave request
        $stmt = $conn->prepare("INSERT INTO leave_requests (user_id, leave_type, start_date, end_date, reason_for_leave, request_timestamp, status) VALUES (?, ?, ?, ?, ?, NOW(), 'pending')");
        $stmt->bind_param("issss", $user_id, $leave_type, $start_date, $end_date, $reason);
        $stmt->execute();
        $stmt->close();

        // Deduct leave balance
        $stmt = $conn->prepare("UPDATE users SET leave_balance = leave_balance - ? WHERE id = ?");
        $stmt->bind_param("ii", $days, $user_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Transaction failed.']);
    }

    $conn->close();
}
?>
