<?php
session_start();

if (!isset($_SESSION["role"], $_SESSION["user_id"], $_SESSION["firstname"]) || $_SESSION["role"] !== 'master') {
    header('Location: ../index.php');
    exit();
}


require_once '../config.php';
$pageTitle = "Manager Dashboard";
$loggedInUserId = $_SESSION['user_id'];
$firstname = $_SESSION['firstname'];

$leaveRequest = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $leaveId = intval($_GET['id']);
    if ($stmt = $conn->prepare("
        SELECT lr.*, u.firstname, u.lastname 
        FROM leave_requests lr 
        JOIN users u ON lr.user_id = u.id 
        WHERE lr.id = ?
    ")) {
        $stmt->bind_param("i", $leaveId);
        $stmt->execute();
        $result = $stmt->get_result();
        $leaveRequest = $result->fetch_assoc();
        $stmt->close();
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
            <h1>Review Leave</h1>
          </div>
        </div>

        <!-- Divider -->
        <hr class="my-8" />

    <?php if ($leaveRequest): ?>
      <form method="post" action="controllers/process.leave.action.controller.php">
        <input type="hidden" name="request_id" value="<?php echo $leaveRequest['id']; ?>">

        <div class="row mb-4">
          <div class="col-md-6">
            <label class="form-label">Employee Name</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($leaveRequest['firstname'] . ' ' . $leaveRequest['lastname']); ?>" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Leave Type</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($leaveTypeLabels[$leaveRequest['leave_type']] ?? ucfirst($leaveRequest['leave_type'])); ?>" readonly>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-6">
            <label class="form-label">Start Date</label>
            <input type="text" class="form-control" value="<?php echo date("F j, Y", strtotime($leaveRequest['start_date'])); ?>" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">End Date</label>
            <input type="text" class="form-control" value="<?php echo date("F j, Y", strtotime($leaveRequest['end_date'])); ?>" readonly>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-6">
            <label class="form-label">Duration</label>
            <input type="text" class="form-control" 
              value="<?php echo ((strtotime($leaveRequest['end_date']) - strtotime($leaveRequest['start_date'])) / (60 * 60 * 24)) + 1; ?> days" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Submitted On</label>
            <input type="text" class="form-control" 
              value="<?php echo date("F j, Y, g:i A", strtotime($leaveRequest['request_timestamp'])); ?>" readonly>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label">Current Status</label>
          <input type="text" class="form-control" value="<?php echo ucfirst($leaveRequest['status']); ?>" readonly>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-start gap-3">
          <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
          <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
          <a href="index.php" class="btn btn-secondary">Back</a>
        </div>
      </form>

    <?php else: ?>
      <div class="alert alert-warning">
        Leave request not found.
      </div>
    <?php endif; ?>
  </div>
</main>


<!-- End Main -->

<?php if (isset($_SESSION['flash_success'])): ?>
  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-success" id="successModalLabel">Success</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="closeSuccessBtn">Close</button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
  <!-- Error Modal -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorModalLabel">Error</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const successModal = document.getElementById('successModal');
    const errorModal = document.getElementById('errorModal');
    
    if (successModal) {
      new bootstrap.Modal(successModal).show();
    }

    if (errorModal) {
      new bootstrap.Modal(errorModal).show();
    }
  });


  
  document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const result = urlParams.get('result');

    if (result === 'success') {
      const modal = new bootstrap.Modal(document.getElementById('successModal'));
      modal.show();
    } else if (result === 'error') {
      const modal = new bootstrap.Modal(document.getElementById('errorModal'));
      modal.show();
    }
  });

  if (window.history.replaceState) {
    window.history.replaceState({}, document.title, window.location.pathname + window.location.search.replace(/(&?result=(success|error))/, ''));
  }

  document.addEventListener('DOMContentLoaded', function () {
    const successModalElement = document.getElementById('successModal');
    const errorModalElement = document.getElementById('errorModal');

    if (successModalElement) {
      const successModal = new bootstrap.Modal(successModalElement);
      successModal.show();
    }

    if (errorModalElement) {
      const errorModal = new bootstrap.Modal(errorModalElement);
      errorModal.show();
    }

    const closeSuccessBtn = document.getElementById('closeSuccessBtn');
    if (closeSuccessBtn) {
      closeSuccessBtn.addEventListener('click', function () {
        setTimeout(function () {
          window.location.href = 'pending_leave_application_list.php';
        }, 1000); // 1 seconds
      });
    }
  });

</script>




    <!--Footer-->
    <?php include 'includes/footer.php';?>
    <!--End Footer-->