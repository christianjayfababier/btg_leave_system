 <?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !='staff') {
    header('Location: ../index.php');
    exit();

    
}

$pageTitle = "Staff Dashboard";

// Include database connection
require_once '../config.php';



$staffId = $_SESSION['id'];
$leaveBalance = 0;

$sql = "SELECT leave_balance FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("i", $staffId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $leaveBalance = $row['leave_balance'];
}

$stmt->close();
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

                    <!-- Text -->
                    <div class="fs-4 fw-semibold"><?php echo $leaveBalance; ?></div>
                  </div>
                  <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-lg bg-body text-primary">
                      <i class="fs-4" data-duoicon="credit-card"></i>
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

                    <!-- Text -->
                    <div class="fs-4 fw-semibold">#</div>
                  </div>
                  <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-lg bg-body text-primary">
                      <i class="fs-4" data-duoicon="clock"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
         
    
        </div>

        <div class="row">
          <div class="col-12 col-xxl-8">
            <!-- Performance -->
            <div class="card mb-6">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="fs-6 mb-0">Pending Request</h3>
                  </div>
                  <div class="col-auto fs-sm me-n3">
                    <span class="material-symbols-outlined text-primary me-1">circle</span>
                    Total
                  </div>
                  <div class="col-auto fs-sm">
                    <span class="material-symbols-outlined text-dark me-1">circle</span>
                    Tracked
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas class="chart-canvas" id="userPerformanceChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Projects -->
            <div class="card mb-6 mb-xxl-0">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="fs-6 mb-0">Active projects</h3>
                  </div>
                  <div class="col-auto my-n3 me-n3">
                    <a class="btn btn-sm btn-link" href="./projects/projects.html">
                      Browse all
                      <span class="material-symbols-outlined">arrow_right_alt</span>
                    </a>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead>
                    <th class="fs-sm">Title</th>
                    <th class="fs-sm">Status</th>
                    <th class="fs-sm">Author</th>
                    <th class="fs-sm">Team</th>
                  </thead>
                  <tbody>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar">
                            <img class="avatar-img" src="./assets/img/projects/project-1.png" alt="..." />
                          </div>
                          <div class="ms-4">
                            <div>Filters AI</div>
                            <div class="fs-sm text-body-secondary">Updated on Apr 10, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-success-subtle text-success">Ready to Ship</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                          </div>
                          Michael Johnson
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Michael Johnson">
                            <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Olivia Davis">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Jessica Miller">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar">
                            <img class="avatar-img" src="./assets/img/projects/project-2.png" alt="..." />
                          </div>
                          <div class="ms-4">
                            <div>Design landing page</div>
                            <div class="fs-sm text-body-secondary">Created on Mar 05, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-danger-subtle text-danger">Cancelled</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                          </div>
                          Emily Thompson
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Olivia Davis">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Jessica Miller">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar text-primary">
                            <i class="fs-4" data-duoicon="book-3"></i>
                          </div>
                          <div class="ms-4">
                            <div>Update documentation</div>
                            <div class="fs-sm text-body-secondary">Created on Jan 22, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-secondary-subtle text-secondary">In Testing</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                          </div>
                          Michael Johnson
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Emily Thompson">
                            <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="John Williams">
                            <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar">
                            <img class="avatar-img" src="./assets/img/projects/project-3.png" alt="..." />
                          </div>
                          <div class="ms-4">
                            <div>Update Touche</div>
                            <div class="fs-sm text-body-secondary">Updated on Apr 14, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-success-subtle text-success">Ready to Ship</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                          Jessica Miller
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Olivia Davis">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Jessica Miller">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="John Williams">
                            <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar text-primary">
                            <i class="fs-4" data-duoicon="box"></i>
                          </div>
                          <div class="ms-4">
                            <div>Add Transactions</div>
                            <div class="fs-sm text-body-secondary">Created on Apr 25, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-light text-body-secondary">Backlog</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          Olivia Davis
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="John Williams">
                            <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Emily Thompson">
                            <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-12 col-xxl-4">
            <!-- Goals -->
            <div class="card mb-6">
  <div class="card-header">
    <div class="row align-items-center">
      <div class="col">
        <h3 class="fs-6 mb-0">Leave History</h3>
      </div>
    </div>
  </div>
  <div class="card-body py-3">
    <div class="list-group list-group-flush">
      <div class="list-group-item px-0">
        <div class="row align-items-center">
          <div class="col ms-n2">
            <h6 class="fs-base fw-normal mb-0">Sample</h6>
           
          </div>
          <div class="col-auto">
            <span class="text-body-secondary">April 17</span>
          </div>
        </div>
      </div>
      <div class="list-group-item px-0">
        <div class="row align-items-center">
          <div class="col ms-n2">
            <h6 class="fs-base fw-normal mb-0">Sample</h6>
           
          </div>
          <div class="col-auto">
            <span class="text-body-secondary">April 15</span>
          </div>
        </div>
      </div>
      <div class="list-group-item px-0">
        <div class="row align-items-center">
          <div class="col ms-n2">
            <h6 class="fs-base fw-normal mb-0">Sample</h6>
            
          </div>
          <div class="col-auto">
            <span class="text-body-secondary">April 12</span>
          </div>
        </div>
      </div>
      <div class="list-group-item px-0">
        <div class="row align-items-center">
          <div class="col ms-n2">
            <h6 class="fs-base fw-normal mb-0">Sample</h6>
           
          </div>
          <div class="col-auto">
            <span class="text-body-secondary">April 2 </span>
          </div>
        </div>
      </div>
      <div class="list-group-item px-0">
        <div class="row align-items-center">
          <div class="col ms-n2">
            <h6 class="fs-base fw-normal mb-0">Sample</h6>
           
          </div>
          <div class="col-auto">
            <span class="text-body-secondary">April 1</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


            <!-- Activity -->
            <div class="card">
              <div class="card-header">
                <h3 class="fs-6 mb-0">Recent activity</h3>
              </div>
              <div class="card-body">
                <ul class="activity">
                  <li data-icon="thumb_up">
                    <div>
                      <h6 class="fs-base mb-1">You <span class="fs-sm fw-normal text-body-secondary ms-1">1hr ago</span></h6>
                      <p class="mb-0">Liked a post by @john_doe</p>
                    </div>
                  </li>
                  <li data-icon="chat_bubble">
                    <div>
                      <h6 class="fs-base mb-1">Jessica Miller <span class="fs-sm fw-normal text-body-secondary ms-1">3hr ago</span></h6>
                      <p class="mb-0">Commented on a photo</p>
                    </div>
                  </li>
                  <li data-icon="share">
                    <div>
                      <h6 class="fs-base mb-1">Emily Thompson <span class="fs-sm fw-normal text-body-secondary ms-1">3hr ago</span></h6>
                      <p class="mb-0">Shared an article: "Top 10 Travel Destinations"</p>
                    </div>
                  </li>
                  <li data-icon="person_add">
                    <div>
                      <h6 class="fs-base mb-1">You <span class="fs-sm fw-normal text-body-secondary ms-1">1 day ago</span></h6>
                      <p class="mb-0">Started following @jane_smith</p>
                    </div>
                  </li>
                  <li data-icon="account_circle">
                    <div>
                      <h6 class="fs-base mb-1">Olivia Davis <span class="fs-sm fw-normal text-body-secondary ms-1">2 days ago</span></h6>
                      <p class="mb-0">Updated profile picture</p>
                    </div>
                  </li>
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