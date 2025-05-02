<?php
// Start session
session_start();

// Include the database connection file
require_once 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get username and password from POST request
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Username and Password are required.";
    } else {
        // Query to check user credentials
        $stmt = $conn->prepare("SELECT id, username, firstname, lastname, role, profile_picture, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch user data
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Store user data in session
                $_SESSION['user_id'] = $user['id']; // ✅ consistent with dashboard
                $_SESSION['username'] = $user['username'];
                $_SESSION['firstname'] = $user['firstname'];
                $_SESSION['lastname'] = $user['lastname'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['profile_picture'] = $user['profile_picture'];


                // Redirect based on role
                switch ($user['role']) {
                    case 'admin':
                        header("Location: admin/index.php");
                        break;
                    case 'manager':
                        header("Location: manager/index.php");
                        break;
                    case 'master':
                        header("Location: master/index.php");
                        break;
                    case 'payments':
                        header("Location: payments/index.php");
                        break;
                    case 'accounting':
                        header("Location: accounting/index.php");
                        break;
                    case 'developer':
                        header("Location: developer/index.php");
                        break;
                    default:
                        header("Location: staff/index.php");
                        break;
                }
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #284941;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .logo {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="card shadow-lg" style="background-color: #335E53;color: #f8f9fa">
        <div class="card-body">

            <!-- Logo Image -->
            <img src="dist/img/btg-logo-wt.png" alt="Logo" class="logo mb-3">


            <h3 class="text-center mb-4">Login</h3>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                    <label for="username" class="form-label">Username</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <label for="password" class="form-label">Password</label>
                </div>

                <button type="submit" class="btn btn-primary w-100" style="background-color: seagreen">Login</button>

                <div class="mb-3 text-center" style="font-size: 0.65em"><br>
                    <span>This system is developed by Bodhitree Group IT. <br>If there are any problems, please contact support.</span>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>