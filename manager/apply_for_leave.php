<?php 
session_start();
require_once '../config.php';

//Ensure the user is logged in and is an staff
if (!isset($_SESSION['role']) && $_SESSION['role'] !='staff') {
header("Location: ../login.php");
exit;
}

// Fetch user_id and firstname from session
$user_id = $_SESSION['user_id'] ?? ""; // Fetch from session
$firstname = $_SESSION['firstname'] ?? ""; // Fetch from session
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
          <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Apply for Leave</a></li>
          <li class="breadcrumb-item active" aria-current="page">New Leave</li>
        </ol>
      </nav>

      <!-- Heading -->
      <h1 class="fs-4 mb-0">New Leave</h1>

    </div>
    <div class="col-12 col-sm-auto mt-4 mt-sm-0">

      <!-- Action -->
      <button class="btn btn-light w-100" type="button">
        Save draft
      </button>

    </div>
  </div>

  <!-- Page content -->
  <div class="row">
    <div class="col">

      <!-- Form -->
      <form action="controllers/apply.leave.request.controller.php" method="POST">
      <div class="mb-4">
        <label class="form-label" for="leave_type">Leave Type</label>
          <select class="form-control" id="leave_type" name="leave_type" required>
            <option value="">Select a leave type</option>
            <option value="sick">Sick Leave</option>
            <option value="vacation">Vacation Leave</option>
            <option value="personal">Personal Leave</option>
          </select>
      </div>

      <div class="mb-4 d-flex gap-3">
        <div class="flex-fill">
          <label class="form-label" for="start_date">Start Date</label>
          <input class="form-control" id="start_date" type="date" name="start_date" required />
        </div>
        <div class="flex-fill">
          <label class="form-label" for="end_date">End Date</label>
          <input class="form-control" id="end_date" type="date" name="end_date" required />
        </div>
      </div>

      <!-- Hidden readonly input for calculated days -->
      <div id="days_difference_wrapper" class="mb-4" style="display: none;">
        <label class="form-label" for="days_difference">Days Difference</label>
        <input 
          class="form-control" 
          id="days_difference" 
          type="text" 
          readonly 
          style="height: 45px; overflow: hidden;"
        />
      </div>

        <div class="mb-4">
          <label class="form-label" for="reason_for_leave">Reason for Leave</label>
          <textarea class="form-control mb-3" id="reason_for_leave" name="reason_for_leave" rows="4" required></textarea>
        </div>
        
        <input type="hidden" name="username" value="<?php echo $username; ?>" />

        <button type="submit" class="btn btn-secondary w-100">
          Submit Leave Request
        </button>
        <button type="reset" class="btn btn-link w-100 mt-3">
          Reset form
        </button>
      </form>

    </div>
  </div>
</main>
<!-- End Main -->


<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Leave request submitted successfully.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- Scripts -->
<script>
  const startDateInput = document.getElementById('start_date');
  const endDateInput = document.getElementById('end_date');
  const daysDifferenceInput = document.getElementById('days_difference');
  const daysWrapper = document.getElementById('days_difference_wrapper');
  const form = document.querySelector('form');

  // Function to set min date to today
  function setMinDateToday() {
    const today = new Date().toISOString().split('T')[0];
    startDateInput.setAttribute('min', today);
    endDateInput.setAttribute('min', today);
  }

  // Calculate days difference
  function calculateDaysDifference() {
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    if (!isNaN(startDate) && !isNaN(endDate)) {
      const timeDiff = endDate - startDate;
      const daysDiff = timeDiff / (1000 * 60 * 60 * 24);

      if (daysDiff >= 0) {
        daysDifferenceInput.value = "Number of Day/s: " + (daysDiff + 1);
        daysWrapper.style.display = 'block';
      } else {
        daysDifferenceInput.value = "";
        daysWrapper.style.display = 'none';
      }
    } else {
      daysDifferenceInput.value = "";
      daysWrapper.style.display = 'none';
    }
  }

  // Handle form submission
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(form);
    fetch(form.action, {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        // Show success modal
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        form.reset(); // Reset the form
        daysWrapper.style.display = 'none'; // Hide days difference
      } else {
        alert(data.message || 'An error occurred while submitting the leave request.');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An unexpected error occurred.');
    });
  });

  // Initialize
  setMinDateToday();
  startDateInput.addEventListener('change', calculateDaysDifference);
  endDateInput.addEventListener('change', calculateDaysDifference);
</script>



<!-- End Scripts -->

    <!--Footer-->
    <?php include 'includes/footer.php';?>
    <!--End Footer-->