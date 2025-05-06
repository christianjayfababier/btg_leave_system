<?php 
session_start();
require_once '../config.php';

// Ensure the user is logged in and is staff
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$firstname = $_SESSION['firstname'];
?>

<!-- Head -->
<?php include 'includes/head.php';?>
<!-- End Head -->

<!-- Toolbar -->
<?php include 'includes/toolbar.php';?>
<!-- End Toolbar -->

<!-- Sidebar -->
<?php include 'includes/sidebar.php';?>
<!-- End Sidebar -->

<!-- Topbar -->
<?php include 'includes/topbar.php';?>
<!-- End Topbar -->

<main class="main px-lg-6">
  <div class="container-lg">
    <!-- Page content -->
    <div class="row align-items-center">
      <div class="col-12 col-md-auto order-md-1 d-flex align-items-center justify-content-center mb-4 mb-md-0">
        <div class="avatar text-info me-2">
          <i class="fs-4" data-duoicon="calendar"></i>
        </div>
        Your Location –&nbsp;<span>8:00 PM</span>
      </div>
      <div class="col-12 col-md order-md-0 text-center text-md-start">
        <h1>Hello, <?php echo htmlspecialchars($firstname); ?></h1>
        <p class="fs-lg text-body-secondary mb-0">Here's a summary of your leave applications.</p>
      </div>
    </div>

    <!-- Divider -->
    <hr class="my-8" />

    <div class="row">
      <div class="col-12 col-xxl-12">
        <!-- Leave Applications -->
        <div class="card mb-6 mb-xxl-0">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col">
                <h3 class="fs-6 mb-0">My Leave Applications</h3>
              </div>
              <div class="col-auto my-n3 me-n3">
                <a class="btn btn-sm btn-link" href="leave_requests.php">
                  View All Leave Requests
                  <span class="material-symbols-outlined">arrow_right_alt</span>
                </a>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th class="fs-sm">Leave Type</th>
                  <th class="fs-sm">Start Date</th>
                  <th class="fs-sm">End Date</th>
                  <th class="fs-sm">Reason</th>
                  <th class="fs-sm">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Use connection from config.php
                if (!$conn) {
                    die("<tr><td colspan='5' class='text-danger'>Database connection failed.</td></tr>");
                }

                // Leave type formatter
                function formatLeaveType($type) {
                    $leaveTypes = [
                        'sick' => 'Sick Leave',
                        'vacation' => 'Vacation Leave',
                        'personal' => 'Personal Leave'
                    ];
                    return $leaveTypes[$type] ?? ucfirst($type) . ' Leave';
                }

                // Get leave requests for current user
                $stmt = $conn->prepare("SELECT * FROM leave_requests WHERE user_id = ? ORDER BY request_timestamp DESC");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $formattedLeaveType = formatLeaveType($row['leave_type']);
                        $status_badge = "<span class='badge bg-light text-body-secondary'>Pending</span>";

                        if ($row['status'] === 'approved') {
                            $status_badge = "<span class='badge bg-success-subtle text-success'>Approved</span>";
                        } elseif ($row['status'] === 'denied') {
                            $status_badge = "<span class='badge bg-danger-subtle text-danger'>Denied</span>";
                        }

                        echo "
                        <tr onclick=\"window.location.href='leave_request_detail.php?id={$row['id']}'\" role='link' tabindex='0'>
                          <td>
                            <div class='d-flex align-items-center'>
                              <div class='ms-4'>
                                <div>{$formattedLeaveType}</div>
                                <div class='fs-sm text-body-secondary'>Requested on " . date("M j, Y", strtotime($row['request_timestamp'])) . "</div>
                              </div>
                            </div>
                          </td>
                          <td>" . date("F j, Y", strtotime($row['start_date'])) . "</td>
                          <td>" . date("F j, Y", strtotime($row['end_date'])) . "</td>
                          <td>" . htmlspecialchars($row['reason_for_leave']) . "</td>
                          <td>$status_badge</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center text-muted'>No leave requests found.</td></tr>";
                }

                $stmt->close();
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Footer -->
<?php include 'includes/footer.php';?>
<!-- End Footer -->
