<?php
session_start();

if (!isset($_SESSION["role"], $_SESSION["user_id"], $_SESSION["firstname"]) || $_SESSION["role"] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

require_once '../config.php';

$pageTitle = "Manager Dashboard";
$loggedInUserId = $_SESSION['user_id'];
$firstname = $_SESSION['firstname'];

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
    
    <!-- Page Title -->
    <div class="row align-items-center mb-4">
      <div class="col">
        <h1 class="mb-0">All Users</h1>
        <p class="fs-lg text-body-secondary">List of registered users in the system</p>
      </div>
    </div>

    <!-- Divider -->
    <hr class="my-4" />

    <!-- All Users Table -->
    <div class="card mb-6">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col">
            <h3 class="fs-6 mb-0">Users</h3>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th class="fs-sm">Name</th>
              <th class="fs-sm">Email</th>
              <th class="fs-sm">Role</th>
              <th class="fs-sm">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include 'includes/db.php'; // Make sure this connects to your database
            $query = "SELECT id, firstname, lastname, email, role, status FROM users";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0):
              while ($user = mysqli_fetch_assoc($result)):
            ?>
                <tr class="clickable-row" data-href="view_user.php?id=<?php echo $user['id']; ?>">
                  <td>
                    <strong><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></strong>
                  </td>
                  <td><?php echo htmlspecialchars($user['email']); ?></td>
                  <td><?php echo ucfirst($user['role']); ?></td>
                  <td>
                    <span class="badge <?php echo $user['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                      <?php echo ucfirst($user['status']); ?>
                    </span>
                  </td>
                </tr>
            <?php
              endwhile;
            else:
            ?>
              <tr>
                <td colspan="4" class="text-center text-muted">No users found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</main>
<!-- End Main -->

<!-- Footer -->
<?php include 'includes/footer.php'; ?>
<!-- End Footer -->

<!-- Clickable Row Script -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  const rows = document.querySelectorAll(".clickable-row");
  rows.forEach(row => {
    row.addEventListener("click", () => {
      window.location = row.dataset.href;
    });
  });
});
</script>