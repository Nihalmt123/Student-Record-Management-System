<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Mark Record System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
</head>

<body style="background-color: #f7f7f7;">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="dashboard.php">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Student Mark Record System
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>

        <!-- Login Form Container -->
        <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
                <h3 class="text-center mb-4">Admin Login</h3>

                <!-- Display error messages -->
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['login_error']) ?></div>
                    <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter Username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="index.php" class="btn btn-link">Back</a>
                    <a href="login_n.php" class="btn btn-link">Admin Login</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


<?php
session_start();
include('dbconnect.php');
if (isset($_POST['username']) && isset($_POST['password'])) {
    $Username = $_POST['username'];
    $Password = $_POST['password'];
    /*........... query for login.............*/
    $sql = "SELECT * FROM users WHERE username = '$Username' and password = '$Password'";
    $data = mysqli_query($db, $sql);   /*include two variable database($db) and query($sql) and finally store $data variable */
    $result = mysqli_num_rows($data);/*data are feech then check how many data are feetch*/
    if ($result == 1) {
        $row = mysqli_fetch_assoc($data); // Fetch row as associative array
        $_SESSION['user_id'] = $row['id'];

        $_SESSION['username'] = $Username;
        header('location:admin/admindash.php'); /*redirect page whene you want*/
    } else {
        echo "failed";
    }
}
?>