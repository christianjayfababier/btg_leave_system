<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION["role"], $_SESSION["user_id"]) || $_SESSION["role"] !== 'accounting') {
    header('Location: ../index.php');
    exit();
}

$loggedInUserId = $_SESSION['user_id'];
$leaveTypeLabels = [
  'sick' => 'Sick Leave',
  'vacation' => 'Vacation Leave',
  'personal' => 'Personal Leave'
];

$pendingRequests = [];

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
          <h1>Pending Leave Application List</h1>
          <p class="fs-sm text-body-secondary mb-0">Review all pending leave applications assigned to you.</p>
          </div>
        </div>

        <!-- Divider -->
        <hr class="my-8" />

    <div class="card mb-6">
      <div class="card-header">
        <h3 class="fs-6 mb-0">Pending Leave Applications</h3>
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
                  <td><?php echo date("F j, Y", strtotime($leave['start_date'])); ?></td>
                  <td><?php echo date("F j, Y", strtotime($leave['end_date'])); ?></td>
                  <td>
                    <?php
                      $start = strtotime($leave['start_date']);
                      $end = strtotime($leave['end_date']);
                      echo ($end - $start) / (60 * 60 * 24) + 1;
                    ?> days
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
  </div>
</main>

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

<!-- End Main -->


    <!--Footer-->
    <?php include 'includes/footer.php';?>
    <!--End Footer-->