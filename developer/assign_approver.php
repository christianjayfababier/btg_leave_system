<?php
session_start();
require_once '../config.php';

// Ensure the user is logged in and is a manager
if (!isset($_SESSION["role"], $_SESSION["user_id"], $_SESSION["firstname"]) || $_SESSION["role"] !== 'developer') {
  header('Location: ../index.php');
  exit();
}

$success = '';
$error = '';

// Handle assignment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_action'])) {
    $approver = intval($_POST['approver']);
    $assignees = isset($_POST['assignees']) ? $_POST['assignees'] : [];

    // Delete old assignments for that approver
    mysqli_query($conn, "DELETE FROM user_assignments WHERE approver_id = $approver");

    // Insert new ones
    foreach ($assignees as $assigneeId) {
        $assigneeId = intval($assigneeId);
        mysqli_query($conn, "INSERT INTO user_assignments (assignee_id, approver_id, created_at) VALUES ($assigneeId, $approver, NOW())");
    }

    $success = "Approver assignments updated.";
}

// Set selected approver if passed via GET or POST
$selectedApprover = isset($_GET['approver']) ? intval($_GET['approver']) : (isset($_POST['approver']) ? intval($_POST['approver']) : 0);

// Get current assignees for the selected approver
$assignedUsers = [];
if ($selectedApprover) {
    $result = mysqli_query($conn, "SELECT assignee_id FROM user_assignments WHERE approver_id = $selectedApprover");
    while ($row = mysqli_fetch_assoc($result)) {
        $assignedUsers[] = $row['assignee_id'];
    }
}
?>

<!-- Includes -->
<?php include 'includes/head.php'; ?>
<?php include 'includes/toolbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<!-- Main -->
<main class="main px-lg-6">
  <div class="container-lg">

    <!-- Page Header -->
    <div class="row align-items-center mb-7">
      <div class="col-auto">
        <div class="avatar avatar-xl rounded text-primary">
          <i class="fs-2" data-duoicon="shield_person"></i>
        </div>
      </div>
      <div class="col">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="#">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Assign Approver</li>
          </ol>
        </nav>
        <h1 class="fs-4 mb-0">Assign Approvers</h1>
      </div>
    </div>

    <!-- Notifications -->
    <?php if ($success): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Approver Select Form -->
    <form method="GET" class="mb-5">
      <div class="card">
        <div class="card-header">
          <h4 class="fs-6 mb-0">Choose Approver</h4>
        </div>
        <div class="card-body">
          <select name="approver" class="form-select" onchange="this.form.submit()">
            <option value="">-- Select Approver --</option>
            <?php
            $approvers = mysqli_query($conn, "SELECT id, firstname, lastname FROM users WHERE role IN ('admin','manager','master','accounting')");
            while ($approver = mysqli_fetch_assoc($approvers)) {
              $selected = ($approver['id'] == $selectedApprover) ? 'selected' : '';
              echo "<option value=\"{$approver['id']}\" $selected>" . htmlspecialchars($approver['firstname'] . ' ' . $approver['lastname']) . "</option>";
            }
            ?>
          </select>
        </div>
      </div>
    </form>

    <!-- Assignee Selection Form -->
    <?php if ($selectedApprover): ?>
      <form method="POST">
        <input type="hidden" name="approver" value="<?php echo $selectedApprover; ?>">
        <input type="hidden" name="assign_action" value="1">

        <div class="card mb-6">
          <div class="card-header">
            <h4 class="fs-6 mb-0">Assign Users to Approver</h4>
          </div>
          <div class="card-body">
            <div class="row row-cols-1 row-cols-md-2 g-4">
              <?php
              $users = mysqli_query($conn, "SELECT id, firstname, lastname, role FROM users ORDER BY firstname ASC");
              while ($user = mysqli_fetch_assoc($users)) {
                $checked = in_array($user['id'], $assignedUsers) ? 'checked' : '';
                $inputId = 'user_' . $user['id'];
              ?>
                <div class="col">
                  <div class="form-switch d-flex align-items-center">
                    <input class="form-check-input me-2" type="checkbox" name="assignees[]" value="<?php echo $user['id']; ?>" id="<?php echo $inputId; ?>" <?php echo $checked; ?>>
                    <label class="form-check-label" for="<?php echo $inputId; ?>">
                      <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) . " ({$user['role']})"; ?>
                    </label>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Save Assignments</button>
      </form>
    <?php endif; ?>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
