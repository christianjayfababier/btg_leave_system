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
      <form>
      <div class="mb-4">
        <label class="form-label" for="leave_type">Leave Type</label>
          <select class="form-control" id="leave_type">
            <option value="">Select a leave type</option>
            <option value="sick">Sick Leave</option>
            <option value="vacation">Vacation Leave</option>
            <option value="personal">Personal Leave</option>
          </select>
      </div>

      <div class="mb-4 d-flex gap-3">
        <div class="flex-fill">
          <label class="form-label" for="start_date">Start Date</label>
          <input class="form-control" id="start_date" type="date" />
        </div>
        <div class="flex-fill">
          <label class="form-label" for="end_date">End Date</label>
          <input class="form-control" id="end_date" type="date" />
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
          <label class="form-label" for="phone">Phone</label>
          <input type="text" class="form-control mb-3" id="phone" placeholder="(___)___-____"
            data-inputmask="'mask': '(999)999-9999'">
        </div>
        <div class="mb-4">
          <label class="form-label" for="location">Location</label>
          <input class="form-control" id="location" type="text" />
        </div>
        <div class="mb-4">
          <label class="form-label mb-0" for="tiptapExample">About</label>
          <div class="form-text mt-0 mb-3">
            A brief description of the customer.
          </div>
          <di class="form-control" id="tiptapExample"></di>
        </div>
        <div class="mb-7">
          <label for="dropzone">Files</label>
          <div class="form-text mt-0 mb-3">
            Attach files to this customer.
          </div>
          <div class="dropzone" id="dropzone"></div>
        </div>
        <button type="submit" class="btn btn-secondary w-100">
          Save customer
        </button>
        <button type="reset" class="btn btn-link w-100 mt-3">
          Reset form
        </button>
      </form>

    </div>
  </div>
</main>
<!-- End Main -->


<!-- Scripts -->
<script>
  const startDateInput = document.getElementById('start_date');
  const endDateInput = document.getElementById('end_date');
  const daysDifferenceInput = document.getElementById('days_difference');
  const daysWrapper = document.getElementById('days_difference_wrapper');

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
        daysDifferenceInput.value = "Number of Day/s: " + daysDiff + " Days";
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

  // Initialize
  setMinDateToday();

  startDateInput.addEventListener('change', calculateDaysDifference);
  endDateInput.addEventListener('change', calculateDaysDifference);
</script>



<!-- End Scripts -->

    <!--Footer-->
    <?php include 'includes/footer.php';?>
    <!--End Footer-->