<?php
session_start();

if (!isset($_SESSION["role"], $_SESSION["user_id"], $_SESSION["firstname"]) || $_SESSION["role"] !== 'manager') {
    header('Location: ../index.php');
    exit();
}

require_once '../config.php';

$pageTitle = "Manager Dashboard";
$loggedInUserId = $_SESSION['user_id'];
$firstname = $_SESSION['firstname'];

$leaveBalance = 0;
$upcomingLeave = 'None';
$pendingRequests = [];
$approvedRequests = [];
$leaveTypeLabels = [
  'sick' => 'Sick Leave',
  'vacation' => 'Vacation Leave',
  'personal' => 'Personal Leave'
];

// Get Leave Balance
if ($stmt = $conn->prepare("SELECT leave_balance FROM users WHERE id = ?")) {
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $stmt->bind_result($leaveBalance);
    $stmt->fetch();
    $stmt->close();
}

// Get Upcoming Leave
$currentDate = date('Y-m-d');
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
        $upcomingLeave = ($startDate === $endDate)
            ? date("F j, Y", strtotime($startDate))
            : date("F j", strtotime($startDate)) . "–" . date("j, Y", strtotime($endDate));
    }
    $stmt->close();
}

// Get Approved Requests
if ($stmt = $conn->prepare("
  SELECT leave_type, start_date, end_date, request_timestamp 
  FROM leave_requests 
  WHERE user_id = ? AND status = 'approved' 
  ORDER BY start_date DESC
")) {
    $stmt->bind_param("i", $loggedInUserId);

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $leaveHistory[] = $row;
    }
    $stmt->close();
}

// Get Pending Requests assigned to this manager
if ($stmt = $conn->prepare("
  SELECT 
    lr.id,
    lr.leave_type,
    lr.start_date,
    lr.end_date,
    lr.request_timestamp,
    lr.status,
    u.firstname,
    u.lastname
  FROM leave_requests lr
  JOIN user_assignments ua ON lr.user_id = ua.assignee_id
  JOIN users u ON lr.user_id = u.id
  WHERE ua.approver_id = ?
    AND lr.status = 'pending'
  ORDER BY lr.request_timestamp DESC
")) {
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pendingRequests[] = $row;
    }
    $stmt->close();
}

// Summary Counts (overall)
$pendingCount = $approvedCount = $deniedCount = 0;

// Pending
if ($stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM leave_requests lr
    JOIN user_assignments ua ON lr.user_id = ua.assignee_id
    WHERE ua.approver_id = ? AND lr.status = 'pending'
")) {
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $stmt->bind_result($pendingCount);
    $stmt->fetch();
    $stmt->close();
}

// Approved
if ($stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM leave_requests lr
    JOIN user_assignments ua ON lr.user_id = ua.assignee_id
    WHERE ua.approver_id = ? AND lr.status = 'approved'
")) {
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $stmt->bind_result($approvedCount);
    $stmt->fetch();
    $stmt->close();
}

// Denied
if ($stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM leave_requests lr
    JOIN user_assignments ua ON lr.user_id = ua.assignee_id
    WHERE ua.approver_id = ? AND lr.status = 'denied'
")) {
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $stmt->bind_result($deniedCount);
    $stmt->fetch();
    $stmt->close();
}


//Approved Requests
if ($stmt = $conn->prepare("
  SELECT leave_type, start_date, end_date, request_timestamp 
  FROM leave_requests 
  WHERE user_id = ? AND status = 'approved' 
  ORDER BY start_date DESC
")) {
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $leaveHistory[] = $row;
    }
    $stmt->close();
}

if ($stmt = $conn->prepare("
  SELECT 
    lr.leave_type, 
    lr.start_date, 
    lr.end_date, 
    lr.request_timestamp,
    u.firstname, 
    u.lastname
  FROM leave_requests lr
  JOIN user_assignments ua ON lr.user_id = ua.assignee_id
  JOIN users u ON lr.user_id = u.id
  WHERE ua.approver_id = ? AND lr.status = 'approved'
  ORDER BY lr.start_date DESC
")) {
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $approvedRequests[] = $row;
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
  WHERE ua.approver_id = ?
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



$deniedRequests = [];

if ($stmt = $conn->prepare("
  SELECT 
    lr.id,
    lr.leave_type,
    lr.start_date,
    lr.end_date,
    lr.request_timestamp,
    u.firstname,
    u.lastname
  FROM leave_requests lr
  JOIN user_assignments ua ON lr.user_id = ua.assignee_id
  JOIN users u ON lr.user_id = u.id
  WHERE ua.approver_id = ? AND lr.status = 'denied'
  ORDER BY lr.start_date DESC
  LIMIT 5
")) {
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $deniedRequests[] = $row;
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
            <p class="fs-lg text-body-secondary mb-0">This is your Manager Dashboard. </p>
          </div>
        </div>

        <!-- Divider -->
        <hr class="my-8" />

        
    

       <!-- Summary Cards -->
<div class="row">
  <div class="col-12 col-md-4 mb-4">
    <div class="card bg-body-tertiary border-transparent">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col">
            <h4 class="fs-sm fw-normal text-body-secondary mb-1">Pending Requests</h4>
          </div>
          <div class="col-auto">
            <div class="fs-4 fw-semibold"><?php echo $pendingCount; ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4 mb-4">
    <div class="card bg-body-tertiary border-transparent">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col">
            <h4 class="fs-sm fw-normal text-body-secondary mb-1">Approved Requests</h4>
          </div>
          <div class="col-auto">
            <div class="fs-4 fw-semibold"><?php echo $approvedCount; ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4 mb-4">
    <div class="card bg-body-tertiary border-transparent">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col">
            <h4 class="fs-sm fw-normal text-body-secondary mb-1">Denied Requests</h4>
          </div>
          <div class="col-auto">
            <div class="fs-4 fw-semibold"><?php echo $deniedCount; ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
        <div class="row">
          <div class="col-12 col-xxl-8">


           <!-- Pending Leave Applications -->
            <div class="card mb-6">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="fs-6 mb-0">Pending Leave Applications</h3>
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
                  <tr class="clickable-row" data-href="review_leave.php?id=<?php echo $leave['id']; ?>">
                    <td>
                      <div class="d-flex flex-column">
                        <strong><?php echo htmlspecialchars($leave['firstname'] . ' ' . $leave['lastname']); ?></strong>
                        <div>
                          <?php 
                            $type = $leave['leave_type'];
                            echo htmlspecialchars($leaveTypeLabels[$type] ?? ucfirst($type)); 
                          ?>
                        </div>
                        <div class="fs-sm text-body-secondary">
                          Submitted on <?php echo date("M j, Y", strtotime($leave['request_timestamp'])); ?>
                        </div>
                      </div>
                    </td>
                    <td>
                      <?php echo date("F j, Y", strtotime($leave['start_date'])); ?>
                    </td>
                    <td>
                      <?php echo date("F j, Y", strtotime($leave['end_date'])); ?>
                    </td>
                    <td>
                      <?php
                        $start = strtotime($leave['start_date']);
                        $end = strtotime($leave['end_date']);
                        echo ($end - $start) / (60 * 60 * 24) + 1;
                      ?> Day/s
                    </td>
                    <td><span class="badge bg-warning">Pending</span></td>
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



            <!-- Approved Requests -->
            <div class="card mb-6 mb-xxl-0">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="fs-6 mb-0">Approved Requests</h3>
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
                  <?php if (!empty($approvedRequests)): ?>
                    <?php foreach ($approvedRequests as $leave): ?>
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
                            Submitted on <?php echo date("M j, Y", strtotime($leave['request_timestamp'])); ?>
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
                        <span class="badge bg-light text-body-secondary"><?php echo $days; ?> <Data></Data>Day/s</span>
                      </td>

        
                      <!-- Status -->
                      <td>
                        <?php
                          $today = strtotime(date('Y-m-d'));
                          $status = $end < $today ? 'Completed' : 'Upcoming';
                          $statusClass = $end < $today ? 'bg-success' : 'bg-info';
                        ?>
                        <span class="badge bg-success">Approved</span>
                        <div class="fs-xs text-body-secondary"><?php echo $status; ?></div>
                      </td>


                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                  <td colspan="5" class="text-center text-muted">No approved leave requests found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>

                              </table>
                            </div>
            </div>


            <!-- Denied Requests -->
            <div class="card mt-6 mb-6 mb-xxl-0">
              <div class="card-header">
              <div class="d-flex justify-content-between align-items-center">
                <h3 class="fs-6 mb-0">Denied Requests</h3>
                <a href="denied_list.php" class="btn btn-sm custom-view-all">View All →</a>
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
                    <?php if (!empty($deniedRequests)): ?>
                      <?php foreach ($deniedRequests as $leave): ?>
                        <tr>
                          <td>
                            <div class="d-flex flex-column">
                              <strong><?php echo htmlspecialchars($leave['firstname'] . ' ' . $leave['lastname']); ?></strong>
                              <div class="fs-sm text-body-secondary">
                                <?php echo htmlspecialchars($leaveTypeLabels[$leave['leave_type']] ?? ucfirst($leave['leave_type'])); ?>
                              </div>
                              <div class="fs-sm text-body-secondary">
                                Submitted on <?php echo date("M j, Y", strtotime($leave['request_timestamp'])); ?>
                              </div>
                            </div>
                          </td>
                          <td><?php echo date("F j, Y", strtotime($leave['start_date'])); ?></td>
                          <td><?php echo date("F j, Y", strtotime($leave['end_date'])); ?></td>
                          <td>
                            <?php
                              $start = strtotime($leave['start_date']);
                              $end = strtotime($leave['end_date']);
                              echo ($end - $start) / (60 * 60 * 24) + 1; ?> Day/s
                          </td>
                          <td><span class="badge bg-danger">Denied</span></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr><td colspan="5" class="text-center text-muted">No denied leave requests found.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>


            </div>
            <div class="col-12 col-xxl-4">


                       


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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('.clickable-row');
    rows.forEach(row => {
      row.style.cursor = 'pointer';
      row.addEventListener('click', () => {
        window.location.href = row.dataset.href;
      });
    });
  });
</script>



    <!--Footer-->
    <?php include 'includes/footer.php';?>
    <!--End Footer-->