<?php
session_start();

if (!isset($_SESSION["role"], $_SESSION["user_id"], $_SESSION["firstname"]) || $_SESSION["role"] !== 'master') {
    header('Location: ../index.php');
    exit();
}

require_once '../config.php';
?>

<!-- Head -->
<?php include 'includes/head.php'; ?>
<!-- End Head -->

<!-- Toolbar -->
<?php include 'includes/toolbar.php'; ?>
<!-- End Toolbar -->

<!-- Sidebar -->
<?php include 'includes/sidebar.php'; ?>
<!-- End Sidebar -->

<!-- Topbar -->
<?php include 'includes/topbar.php'; ?>
<!-- End Topbar -->

<!-- Main -->
<main class="main px-lg-6">
  <div class="container-lg">

    <!-- Page header -->
    <div class="row align-items-center mb-7">
      <div class="col-auto">
        <div class="avatar avatar-xl rounded text-primary">
          <i class="fs-2" data-duoicon="user"></i>
        </div>
      </div>
      <div class="col">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Users</li>
          </ol>
        </nav>
        <h1 class="fs-4 mb-0">User Management</h1>
      </div>
      <div class="col-12 col-sm-auto mt-4 mt-sm-0">
        <a class="btn btn-secondary d-block" href="add_user.php">
          <span class="material-symbols-outlined me-1">add</span> New User
        </a>
      </div>
    </div>

    <!-- Table -->
    <div class="table-responsive mb-7">
      <table class="table table-hover table-select table-round align-middle mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Role</th>
            <th>Leave Balance</th>
            <th style="width: 0px"></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT id, firstname, lastname, username, email, role, leave_balance FROM users";
          $result = mysqli_query($conn, $query);

          if ($result && mysqli_num_rows($result) > 0):
            while ($user = mysqli_fetch_assoc($result)):
              ?>
              <tr onclick="window.location.href='edit_user.php?id=<?php echo $user['id']; ?>'" role="link" tabindex="0">
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm bg-primary text-white me-3">
                      <?php echo strtoupper(substr($user['firstname'], 0, 1) . substr($user['lastname'], 0, 1)); ?>
                    </div>
                    <div>
                      <div><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></div>
                    </div>
                  </div>
                </td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo ucfirst($user['role']); ?></td>
                <td><?php echo (int)$user['leave_balance']; ?> day(s)</td>
                <td>
                  <div class="dropdown text-end">
                    <button class="btn btn-sm btn-link text-body-tertiary" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <span class="material-symbols-outlined scale-125">more_horiz</span>
                    </button>
                  </div>
                </td>
              </tr>
            <?php
            endwhile;
          else:
            echo '<tr><td colspan="6" class="text-center text-muted">No users found.</td></tr>';
          endif;
          ?>
        </tbody>
      </table>
    </div>

  </div>
</main>
<!-- End Main -->

<!-- Footer -->
<?php include 'includes/footer.php'; ?>
<!-- End Footer -->
