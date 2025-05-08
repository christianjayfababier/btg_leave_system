<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION["role"], $_SESSION["user_id"], $_SESSION["firstname"]) || $_SESSION["role"] !== 'master') {
  header('Location: ../index.php');
  exit();
}

$loggedInUserId = $_SESSION['user_id'];
$leaveTypeLabels = [
  'sick' => 'Sick Leave',
  'vacation' => 'Vacation Leave',
  'personal' => 'Personal Leave'
];

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
          <h1>Denied Application List</h1>
          </div>
        </div>

        <!-- Divider -->
        <hr class="my-8" />

    <div class="card mb-6">
      <div class="card-header">
        <h3 class="fs-6 mb-0">Denied Applications</h3>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th class="fs-sm">Employee</th>
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
                  <td><strong><?php echo htmlspecialchars($leave['firstname'] . ' ' . $leave['lastname']); ?></strong></td>
                  <td><?php echo htmlspecialchars($leaveTypeLabels[$leave['leave_type']] ?? ucfirst($leave['leave_type'])); ?></td>
                  <td><?php echo date("F j, Y", strtotime($leave['start_date'])); ?></td>
                  <td><?php echo date("F j, Y", strtotime($leave['end_date'])); ?></td>
                  <td>
                    <?php echo (strtotime($leave['end_date']) - strtotime($leave['start_date'])) / (60 * 60 * 24) + 1; ?> days
                  </td>
                  <td><span class="badge bg-danger">Denied</span></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="6" class="text-center text-muted">No denied leave requests found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- End Main -->


    <!--Footer-->
    <?php include 'includes/footer.php';?>
    <!--End Footer-->