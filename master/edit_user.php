<?php
session_start();
require_once '../config.php';

// Ensure the user is logged in and is a manager
if (!isset($_SESSION["role"], $_SESSION["user_id"], $_SESSION["firstname"]) || $_SESSION["role"] !== 'master') {
  header('Location: ../index.php');
  exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: all_users.php");
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "User not found.";
    exit();
}

$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $leave_balance = intval($_POST['leave_balance']);

    $update = "UPDATE users SET 
        firstname='$firstname', 
        lastname='$lastname', 
        username='$username', 
        email='$email', 
        role='$role', 
        leave_balance=$leave_balance 
        WHERE id=$id";

    if (mysqli_query($conn, $update)) {
        header("Location: employee.php");
        exit();
    } else {
        $error = "Failed to update user: " . mysqli_error($conn);
    }
}
?>

<?php include 'includes/head.php'; ?>
<?php include 'includes/toolbar.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<main class="main px-lg-6">
  <div class="container-lg">
    <div class="row align-items-center mb-7">
      <div class="col">
        <h1 class="fs-4">Edit User</h1>
      </div>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="card mb-5">
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
                <?php
                $rolesQuery = "SELECT DISTINCT role FROM users";
                $rolesResult = mysqli_query($conn, $rolesQuery);
                
                if ($rolesResult && mysqli_num_rows($rolesResult) > 0):
                while ($roleRow = mysqli_fetch_assoc($rolesResult)):
                    $roleValue = htmlspecialchars($roleRow['role']);
                    $selected = ($user['role'] === $roleValue) ? 'selected' : '';
                    echo "<option value=\"$roleValue\" $selected>" . ucfirst($roleValue) . "</option>";
                endwhile;
                else:
                echo '<option value="">No roles found</option>';
                endif;
                ?>
            </select>
            </div>

          <div class="mb-3">
            <label class="form-label">Leave Balance</label>
            <input type="number" name="leave_balance" class="form-control" value="<?php echo (int)$user['leave_balance']; ?>" required>
          </div>
          <button type="submit" class="btn btn-primary">Update User</button>
          <a href="employee.php?id=<?php echo $id; ?>" class="btn btn-link">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
