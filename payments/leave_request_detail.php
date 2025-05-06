<?php 
session_start();
require_once '../config.php';

//Ensure the user is logged in and is an staff
if (!isset($_SESSION['role']) && $_SESSION['role'] !='payments') {
header("Location: ../login.php");
exit;
}

// Fetch user_id and firstname from session
$user_id = $_SESSION['user_id'] ?? ""; // Fetch from session
$firstname = $_SESSION['firstname'] ?? ""; // Fetch from session


// Fetch the leave request ID from the URL
$request_id = $_GET['id'] ?? null;

if (!$request_id) {
    die("Invalid leave request ID.");
}

// Fetch the leave request details from the database
$query = "SELECT * FROM leave_requests WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$leave_request = $result->fetch_assoc();
$stmt->close();

if (!$leave_request) {
    die("Leave request not found.");
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
    <!-- Page header -->
    <div class="row align-items-center mb-7">
      <div class="col-auto">
        <!-- Avatar -->
        <div class="avatar avatar-xl rounded text-primary">
          <i class="fs-2" data-duoicon="user"></i>
        </div>
      </div>
      <div class="col">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Leave Requests</a></li>
            <li class="breadcrumb-item active" aria-current="page">Leave Request Details</li>
          </ol>
        </nav>
        <!-- Heading -->
        <h1 class="fs-4 mb-0">Leave Request Details</h1>
      </div>
    </div>

    <!-- Page content -->
    <div class="row">
      <div class="col">
        <!-- Form -->
        <form>
          <div class="mb-4">
            <label class="form-label" for="leave_type">Leave Type</label>
            <input 
              class="form-control" 
              id="leave_type" 
              type="text" 
              value="<?php echo ucfirst($leave_request['leave_type']) . ' Leave'; ?>" 
              readonly 
            />
          </div>

          <div class="mb-4 d-flex gap-3">
            <div class="flex-fill">
              <label class="form-label" for="start_date">Start Date</label>
              <input 
                class="form-control" 
                id="start_date" 
                type="date" 
                value="<?php echo $leave_request['start_date']; ?>" 
                readonly 
              />
            </div>
            <div class="flex-fill">
              <label class="form-label" for="end_date">End Date</label>
              <input 
                class="form-control" 
                id="end_date" 
                type="date" 
                value="<?php echo $leave_request['end_date']; ?>" 
                readonly 
              />
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label" for="reason_for_leave">Reason for Leave</label>
            <textarea 
              class="form-control mb-3" 
              id="reason_for_leave" 
              rows="4" 
              readonly
            ><?php echo $leave_request['reason_for_leave']; ?></textarea>
          </div>

          <div class="mb-4">
            <label class="form-label" for="request_timestamp">Request Submitted On</label>
            <input 
              class="form-control" 
              id="request_timestamp" 
              type="text" 
              value="<?php echo $leave_request['request_timestamp']; ?>" 
              readonly 
            />
          </div>

          <!-- Back to Leave Requests Button -->
          <a href="my_leave_application.php" class="btn btn-secondary w-100">
            Back to Leave Requests
          </a>
        </form>
      </div>
    </div>
  </div>
</main>
<!-- End Main -->



    <!--Footer-->
    <?php include 'includes/footer.php';?>
    <!--End Footer-->