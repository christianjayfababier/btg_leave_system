<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION["role"], $_SESSION["user_id"], $_SESSION["firstname"]) || $_SESSION["role"] !== 'master') {
    header('Location: ../index.php');
    exit();
}

$leaveTypeLabels = [
  'sick' => 'Sick Leave',
  'vacation' => 'Vacation Leave',
  'personal' => 'Personal Leave'
];

$groupedApprovedRequests = [];

$query = "
  SELECT 
    lr.id,
    lr.leave_type,
    lr.start_date,
    lr.end_date,
    lr.request_timestamp,
    requester.firstname AS requester_firstname,
    requester.lastname AS requester_lastname,
    approver.firstname AS approver_firstname,
    approver.lastname AS approver_lastname,
    lr.approved_by
  FROM leave_requests lr
  JOIN users requester ON lr.user_id = requester.id
  JOIN users approver ON lr.approved_by = approver.id
  WHERE lr.status = 'approved'
  ORDER BY lr.approved_by, lr.start_date DESC
";


if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $approverId = $row['approved_by'];
        $groupedApprovedRequests[$approverId]['approver_name'] = $row['approver_firstname'] . ' ' . $row['approver_lastname'];
        $groupedApprovedRequests[$approverId]['requests'][] = $row;
    }
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

<!-- Topbar -->
<?php include 'includes/topbar.php';?>
<!-- End Topbar -->

<!-- Main -->
<main class="main px-lg-6">
  <div class="container-lg">

    <div class="row align-items-center">
      <div class="col-12 col-md-auto order-md-1 d-flex align-items-center justify-content-center mb-4 mb-md-0">
        <div class="avatar text-info me-2">
          <i class="fs-4" data-duoicon="world"></i>
        </div>
        San Francisco, CA –&nbsp;<span>8:00 PM</span>
      </div>
      <div class="col-12 col-md order-md-0 text-center text-md-start">
        <h1>All Approved Applications</h1>
      </div>
    </div>

    <!-- Divider -->
    <hr class="my-8" />

    <?php if (!empty($groupedApprovedRequests)): ?>
      <?php foreach ($groupedApprovedRequests as $approverData): ?>
        <div class="card mb-6">
          <div class="card-header">
            <h4 class="mb-0">Approver: <?php echo htmlspecialchars($approverData['approver_name']); ?></h4>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th>Employee</th>
                  <th>Leave Type</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Duration</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($approverData['requests'] as $leave): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($leave['requester_firstname'] . ' ' . $leave['requester_lastname']); ?></td>
                    <td><?php echo htmlspecialchars($leaveTypeLabels[$leave['leave_type']] ?? ucfirst($leave['leave_type'])); ?></td>
                    <td><?php echo date("F j, Y", strtotime($leave['start_date'])); ?></td>
                    <td><?php echo date("F j, Y", strtotime($leave['end_date'])); ?></td>
                    <td><?php echo (strtotime($leave['end_date']) - strtotime($leave['start_date'])) / (60 * 60 * 24) + 1; ?> days</td>
                    <td><span class="badge bg-success">Approved</span></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted">No approved leave requests found.</p>
    <?php endif; ?>

  </div>
</main>
<!-- End Main -->

<!-- Footer -->
<?php include 'includes/footer.php';?>
<!-- End Footer -->
