<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== 'manager') {
    header('Location: ../index.php');
    exit();
}

$pageTitle = "Staff Dashboard";

// Include database connection
require_once '../config.php';

// Initialize variables
$loggedInUserId = $_SESSION['user_id'];
$firstname = $_SESSION['firstname'];
$leaveBalance = 0;
$upcomingLeave = 'None';
$leaveHistory = [];

// Map leave type codes to readable labels
$leaveTypeLabels = [
  'sick' => 'Sick Leave',
  'vacation' => 'Vacation Leave',
  'personal' => 'Personal Leave'
];

// Function to get full leave label
function formatLeaveType($type, $map) {
  return $map[$type] ?? ucfirst($type) . ' Leave';
}


// Get Leave Balance
if ($stmt = $conn->prepare("SELECT leave_balance FROM users WHERE id = ?")) {
  $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $stmt->bind_result($leaveBalance);
    $stmt->fetch();
    $stmt->close();
}

// Get Upcoming Leave
$currentDate = date('Y-m-d'); // Set current date dynamically
if ($stmt = $conn->prepare("
    SELECT start_date, end_date 
    FROM leave_requests 
    WHERE user_id = ? 
      AND start_date >= ? 
      AND status = 'approved' 
    ORDER BY start_date ASC 
    LIMIT 1
")) {
    $stmt->bind_param("is", $loggedInUserId, $currentDate);
    $stmt->execute();
    $stmt->bind_result($startDate, $endDate);
    if ($stmt->fetch()) {
        // Debugging: Check if the query returns data
        error_log("Upcoming leave found: Start - $startDate, End - $endDate");

        // Format the date range
        if ($startDate === $endDate) {
            $upcomingLeave = date("F j, Y", strtotime($startDate));
        } else {
            $upcomingLeave = date("F j", strtotime($startDate)) . "–" . date("j, Y", strtotime($endDate));
        }
    } else {
        // Debugging: No results found
        error_log("No upcoming leave found for user firstname: $firstname");
        $upcomingLeave = 'None';
    }
    $stmt->close();
} else {
    // Debugging: SQL preparation error
    error_log("SQL query preparation failed: " . $conn->error);
}


// Get Leave History
$leaveHistory = []; // Initialize leave history array
$currentDate = date('Y-m-d'); // Current date

if ($stmt = $conn->prepare("
    SELECT leave_type, start_date, end_date 
    FROM leave_requests 
    WHERE user_id = ? 
      AND end_date < ? 
      AND status = 'approved' 
    ORDER BY end_date DESC
")) {
    $stmt->bind_param("is", $loggedInUserId, $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $leaveHistory[] = $row;
    }
    $stmt->close();
}

// Debugging: Log the leave history array
error_log(print_r($leaveHistory, true));


// Get Pending Leave Requests
$pendingRequests = [];

if ($stmt = $conn->prepare("
    SELECT leave_type, start_date, end_date, request_timestamp 
    FROM leave_requests 
    WHERE user_id = ? 
      AND status = 'pending' 
    ORDER BY start_date ASC
")) {
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pendingRequests[] = $row;
    }
    $stmt->close();
}


$recentActivities = [];
if ($stmt = $conn->prepare("
    SELECT 
      lr.id,
      lr.leave_type,
      lr.request_timestamp,
      lr.decision_timestamp,
      lr.status,
      u.firstname AS employee_name,
      m.firstname AS manager_name
    FROM leave_requests lr
    JOIN users u ON lr.user_id = u.id
    JOIN user_assignments ua ON lr.user_id = ua.assignee_id
    JOIN users m ON ua.approver_id = m.id
    WHERE lr.user_id = ?
    ORDER BY GREATEST(COALESCE(lr.decision_timestamp, '0000-00-00'), lr.request_timestamp) DESC
    LIMIT 10
")) {
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recentActivities[] = $row;
    }
    $stmt->close();
}




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


<!-- topbar -->
<?php include 'includes/topbar.php';?>
<!-- End Topbar -->



<!-- Main -->
<main class="main px-lg-6">
      <!-- Content -->
      <div class="container-lg">
        <!-- Page content -->
        <div class="row align-items-center">
          <div class="col-12 col-md-auto order-md-1 d-flex align-items-center justify-content-center mb-4 mb-md-0">
            <div class="avatar text-info me-2">
              <i class="fs-4" data-duoicon="world"></i>
            </div>
            San Francisco, CA –&nbsp;<span>8:00 PM</span>
          </div>
          <div class="col-12 col-md order-md-0 text-center text-md-start">
            <h1>Hello, <?php echo $_SESSION['firstname']?></h1>
            <p class="fs-lg text-body-secondary mb-0">This is you Dashboard. </p>
          </div>
        </div>

        <!-- Divider -->
        <hr class="my-8" />

        <!-- Stats -->
        <div class="row mb-8">
          <div class="col-12 col-md-6 col-xxl-6 mb-4 mb-xxl-0">
            <div class="card bg-body-tertiary border-transparent">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <!-- Heading -->
                    <h4 class="fs-sm fw-normal text-body-secondary mb-1">Leave Balance</h4>         
                  </div>
                  <div class="col-auto">
                    <!-- Leave Balance -->
                    <div>
                    <div class="fs-4 fw-semibold"><?php echo $leaveBalance; ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xxl-6 mb-4 mb-xxl-0">
            <div class="card bg-body-tertiary border-transparent">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <!-- Heading -->
                    <h4 class="fs-sm fw-normal text-body-secondary mb-1">Upcoming Leave</h4>
                  </div>
                  <div class="col-auto">
                    <!-- Upcoming Leave -->
                    <div>
                    <div class="fs-4 fw-semibold"><?php echo $upcomingLeave; ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
         
    
        </div>

        <div class="row">
          <div class="col-12 col-xxl-8">

           <!-- Pending Leave Requests -->
<div class="card mb-6">
  <div class="card-header">
    <div class="row align-items-center">
      <div class="col">
        <h3 class="fs-6 mb-0">Pending Leave Requests</h3>
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
          <th class="fs-sm">Duration</th>
          <th class="fs-sm">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($pendingRequests)): ?>
          <?php foreach ($pendingRequests as $leave): ?>
            <tr>
              <!-- Leave Type + Submitted Date -->
              <td>
                <div class="d-flex align-items-center">
                  <div class="ms-3">
                    <div>
                      <?php 
                        $type = $leave['leave_type'];
                        echo htmlspecialchars($leaveTypeLabels[$type] ?? ucfirst($type)); 
                      ?>
                    </div>
                    <div class="fs-sm text-body-secondary">
                      Submitted on <?php echo date("M j, Y", strtotime($leave['submitted_date'] ?? $leave['start_date'])); ?>
                    </div>
                  </div>
                </div>
              </td>

              <!-- Start Date -->
              <td>
                <span class="fs-sm text-body-secondary">
                  <?php echo date("F j, Y", strtotime($leave['start_date'])); ?>
                </span>
              </td>

              <!-- End Date -->
              <td>
                <span class="fs-sm text-body-secondary">
                  <?php echo date("F j, Y", strtotime($leave['end_date'])); ?>
                </span>
              </td>

              <!-- Duration -->
              <td>
                <?php
                  $start = strtotime($leave['start_date']);
                  $end = strtotime($leave['end_date']);
                  $days = ($end - $start) / (60 * 60 * 24) + 1;
                ?>
                <span class="badge bg-light text-body-secondary"><?php echo $days; ?> days</span>
              </td>

              <!-- Status -->
              <td>
                <span class="badge bg-warning">Pending</span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center text-muted">No pending requests found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>



            <!-- Leave History -->
            <div class="card mb-6 mb-xxl-0">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="fs-6 mb-0">Leave History</h3>
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
                      <th class="fs-sm">Duration</th>
                      <th class="fs-sm">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                <?php if (!empty($leaveHistory)): ?>
                  <?php foreach ($leaveHistory as $leave): ?>
                    <tr>
                      <!-- Leave Type -->
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="ms-3">
                            <div>
                              <?php 
                                $type = $leave['leave_type'];
                                echo htmlspecialchars($leaveTypeLabels[$type] ?? ucfirst($type)); 
                              ?>
                            </div>
                            <div class="fs-sm text-body-secondary">
                              Applied on <?php echo date("M j, Y", strtotime($leave['applied_date'] ?? $leave['start_date'])); ?>
                            </div>
                          </div>
                        </div>
                      </td>

                      <!-- Start Date -->
                      <td>
                        <span class="fs-sm text-body-secondary">
                          <?php echo date("F j, Y", strtotime($leave['start_date'])); ?>
                        </span>
                      </td>

                      <!-- End Date -->
                      <td>
                        <span class="fs-sm text-body-secondary">
                          <?php echo date("F j, Y", strtotime($leave['end_date'])); ?>
                        </span>
                      </td>

                      <!-- Duration -->
                      <td>
                        <?php
                          $start = strtotime($leave['start_date']);
                          $end = strtotime($leave['end_date']);
                          $days = ($end - $start) / (60 * 60 * 24) + 1;
                        ?>
                        <span class="badge bg-light text-body-secondary"><?php echo $days; ?> days</span>
                      </td>

                      <!-- Status -->
                      <td>
                        <?php
                          $today = strtotime(date('Y-m-d'));
                          $status = $end < $today ? 'Completed' : 'Ongoing';
                          $statusClass = $end < $today ? 'bg-success' : 'bg-warning';
                        ?>
                        <span class="badge <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted">No leave history found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>

                              </table>
                            </div>
                          </div>

                        </div>
                        <div class="col-12 col-xxl-4">


                        <!-- Leave History Old -->


           <!-- Recennt Activity -->
           <div class="card">
              <div class="card-header">
                <h3 class="fs-6 mb-0">Recent activity</h3>
              </div>
              <div class="card-body">
              <ul class="activity">
              <?php foreach ($recentActivities as $activity): ?>
                <li data-icon="<?php echo $activity['status'] === 'pending' ? 'description' : ($activity['status'] === 'approved' ? 'check_circle' : 'cancel'); ?>">
                  <div>
                    <h6 class="mb-1" style="font-size: 0.87em;">
                      <?php if ($activity['status'] === 'pending'): ?>
                        A new leave request has been submitted by <?php echo htmlspecialchars($activity['employee_name']); ?>.
                      <?php elseif ($activity['status'] === 'approved'): ?>
                        The leave request has been approved by <?php echo htmlspecialchars($activity['manager_name']); ?>.
                      <?php elseif ($activity['status'] === 'denied'): ?>
                        The leave request has been denied by <?php echo htmlspecialchars($activity['manager_name']); ?>.
                      <?php endif; ?>
                    </h6>
                    <div class="text-body-secondary" style="font-size: 0.87em;">
                      <?php
                        $timestamp = $activity['status'] === 'pending' 
                          ? $activity['request_timestamp'] 
                          : $activity['decision_timestamp'];
                        echo date("F j, Y, g:i A", strtotime($timestamp));
                      ?>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>


              </div>
            </div>

            
          </div>
        </div>
      </div>
    </main>

<!-- End Main -->


    <!--Footer-->
    <?php include 'includes/footer.php';?>
    <!--End Footer-->