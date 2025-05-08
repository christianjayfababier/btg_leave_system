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
        $_SESSION['flash_error'] = 'Invalid leave request or action.';
        header("Location: ../review_leave.php?id={$requestId}");
        exit();
    }

    // Verify manager's authorization
    $stmt = $conn->prepare("
        SELECT lr.user_id 
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
        header("Location: ../review_leave.php?id={$requestId}");
        exit();
    }

    $stmt->bind_result($userId);
    $stmt->fetch();
    $stmt->close();

    // Start transaction
    $conn->begin_transaction();
    $newStatus = ($action === 'approve') ? 'approved' : 'denied';

    try {
        if ($newStatus === 'approved') {
            // Approve logic
            $stmt = $conn->prepare("
                UPDATE leave_requests 
                SET status = ?, decision_timestamp = NOW(), approved_by = ? 
                WHERE id = ?
            ");
            if (!$stmt) throw new Exception('Database error during approval.');
            $stmt->bind_param("sii", $newStatus, $managerId, $requestId);
            $stmt->execute();
            $stmt->close();

        } else {
            // Fetch leave dates for denied request
            $stmt = $conn->prepare("SELECT start_date, end_date FROM leave_requests WHERE id = ?");
            $stmt->bind_param("i", $requestId);
            $stmt->execute();
            $stmt->bind_result($start_date, $end_date);
            $stmt->fetch();
            $stmt->close();

            $start = new DateTime($start_date);
            $end = new DateTime($end_date);
            $days = $start->diff($end)->days + 1;

            // Deny the request
            $stmt = $conn->prepare("
                UPDATE leave_requests 
                SET status = ?, decision_timestamp = NOW() 
                WHERE id = ?
            ");
            if (!$stmt) throw new Exception('Database error during denial.');
            $stmt->bind_param("si", $newStatus, $requestId);
            $stmt->execute();
            $stmt->close();

            // Restore leave balance
            $stmt = $conn->prepare("UPDATE users SET leave_balance = leave_balance + ? WHERE id = ?");
            $stmt->bind_param("ii", $days, $userId);
            $stmt->execute();
            $stmt->close();
        }

        $conn->commit();
        $actionMessage = ($newStatus === 'approved') ? 'approved' : 'rejected and balance restored';
        $_SESSION['flash_success'] = "Leave request has been {$actionMessage}.";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['flash_error'] = 'Failed to process leave action: ' . $e->getMessage();
    }

    header("Location: ../review_leave.php?id={$requestId}");
    exit();
} else {
    header('Location: ../index.php');
    exit();
}
