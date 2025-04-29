<?php
session_start();
require_once '../../config.php';

// Ensure the user is logged in
if (!isset($_SESSION['firstname']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../login.php");
    exit;
}

// Fetch firstname from the session
$firstname = $_SESSION['firstname'];

// Debugging: Verify the session firstname
if (empty($firstname)) {
    die("Error: Firstname is not set in the session.");
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $leave_type = isset($_POST['leave_type']) ? htmlspecialchars(trim($_POST['leave_type'])) : '';
    $start_date = isset($_POST['start_date']) ? htmlspecialchars(trim($_POST['start_date'])) : '';
    $end_date = isset($_POST['end_date']) ? htmlspecialchars(trim($_POST['end_date'])) : '';
    $reason_for_leave = isset($_POST['reason_for_leave']) ? htmlspecialchars(trim($_POST['reason_for_leave'])) : '';

    // Validate required fields
    if (empty($leave_type) || empty($start_date) || empty($end_date) || empty($reason_for_leave)) {
        echo "All fields are required.";
        exit;
    }

    // Ensure end_date is not earlier than start_date
    if (strtotime($end_date) < strtotime($start_date)) {
        echo "End date cannot be earlier than start date.";
        exit;
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO leave_requests (firstname, leave_type, start_date, end_date, reason_for_leave, request_timestamp) 
                            VALUES (?, ?, ?, ?, ?, NOW())");

    if ($stmt) {
        // Bind parameters and execute
        $stmt->bind_param("sssss", $firstname, $leave_type, $start_date, $end_date, $reason_for_leave);

        if ($stmt->execute()) {
            // Redirect or display success message
            header("Location: ../success.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
    exit;
}

// Close the database connection
$conn->close();
?>