<?php
session_start();
require_once '../config.php';

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = trim($_POST['role']);
    $leave_balance = intval($_POST['leave_balance']);

    if ($firstname && $lastname && $username && $password && $role && $status) {
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, password, role, , leave_balance, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssi", $firstname, $lastname, $username, $password, $role, $leave_balance);

        if ($stmt->execute()) {
            $success = "User created successfully.";
        } else {
            $error = "Error creating user: " . $conn->error;
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!-- Head -->
<?php include 'includes/head.php'; ?>
<!-- Toolbar -->
<?php include 'includes/toolbar.php'; ?>
<!-- Sidebar -->
<?php include 'includes/sidebar.php'; ?>
<!-- Topbar -->
<?php include 'includes/topbar.php'; ?>

<!-- Main -->
<main class="main px-lg-6">
  <div class="container-lg">

    <!-- Page Header -->
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
            <li class="breadcrumb-item active" aria-current="page">New User</li>
          </ol>
        </nav>
        <h1 class="fs-4 mb-0">New User</h1>
      </div>
      <div class="col-12 col-sm-auto mt-4 mt-sm-0">
        <a href="employee.php" class="btn btn-light w-100">Cancel</a>
      </div>
    </div>

    <!-- Notifications -->
    <?php if ($success): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Form -->
    <div class="row">
      <div class="col">
        <form method="POST">
          <div class="mb-4">
            <label class="form-label">First Name</label>
            <input class="form-control" name="firstname" type="text" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Last Name</label>
            <input class="form-control" name="lastname" type="text" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Username</label>
            <input class="form-control" name="username" type="text" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Password</label>
            <input class="form-control" name="password" type="password" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
              <?php
              $rolesQuery = "SELECT DISTINCT role FROM users";
              $rolesResult = mysqli_query($conn, $rolesQuery);
              if ($rolesResult && mysqli_num_rows($rolesResult) > 0) {
                while ($row = mysqli_fetch_assoc($rolesResult)) {
                  echo '<option value="' . htmlspecialchars($row['role']) . '">' . ucfirst($row['role']) . '</option>';
                }
              } else {
                echo '<option value="employee">Employee</option>';
              }
              ?>
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label">Leave Balance</label>
            <input class="form-control" name="leave_balance" type="number" value="0" min="0" required>
          </div>

          <button type="submit" class="btn btn-secondary w-100">Save User</button>
          <button type="reset" class="btn btn-link w-100 mt-3">Reset Form</button>
        </form>
      </div>
    </div>
  </div>
</main>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>
