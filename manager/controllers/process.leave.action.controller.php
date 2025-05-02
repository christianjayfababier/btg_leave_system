<?php
session_start();

require_once '../../config.php';

// Only allow managers
if (!isset($_SESSION['role'], $_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $managerId = $_SESSION['user_id'];
    $requestId = isset($_POST['request_id']) ? intval($_POST['request_id']) : 0;
    $action = isset($_POST['action']) ? strtolower($_POST['action']) : '';

    if ($requestId <= 0 || !in_array($action, ['approve', 'reject'])) {
        // Invalid input
        $_SESSION['flash_error'] = 'Invalid leave request or action.';
        header('Location: ../index.php');
        exit();
    }

    // Check if manager is allowed to approve/reject this request
    $stmt = $conn->prepare("
        SELECT lr.id 
        FROM leave_requests lr
        JOIN user_assignments ua ON lr.user_id = ua.assignee_id
        WHERE lr.id = ? AND ua.approver_id = ?
    ");
    $stmt->bind_param("ii", $requestId, $managerId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        $_SESSION['flash_error'] = 'You are not authorized to modify this leave request.';
        header('Location: ../index.php');
        exit();
    }
    $stmt->close();

    // Update leave request status
    $newStatus = ($action === 'approve') ? 'approved' : 'denied';

    $sql = "UPDATE leave_requests SET status = ?, decision_timestamp = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        // Log or display the error in dev mode
        $_SESSION['flash_error'] = 'Database error: ' . $conn->error;
        header('Location: ../index.php');
        exit();
    }

    $stmt->bind_param("si", $newStatus, $requestId);


    if ($stmt->execute()) {
        $_SESSION['flash_success'] = "Leave request has been {$newStatus}.";
    } else {
        $_SESSION['flash_error'] = 'Failed to update leave request. Please try again.';
    }

    $stmt->close();
    header('Location: ../index.php');
    exit();
} else {
    // If accessed via GET or other methods
    header('Location: ../index.php');
    exit();
}
