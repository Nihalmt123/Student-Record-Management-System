<?php
// File: student/admin/student/add_student.php

require_once '../../includes/auth.php';     
require_once '../../includes/functions.php'; // For any shared functions (though not directly used in this specific submission logic)
include '../../dbconnect.php';            
// if (!isLoggedIn() || !isAdmin()) { // Assuming isLoggedIn() and isAdmin() are defined in auth.php
//     header('Location: ../../auth/login.php'); // Redirect to login page
//     exit('Access Denied: You do not have permission to add students.');
// }

$message = '';
$messageType = ''; 

if (isset($_POST['submit'])) {
    // Get data from the form
    $ROLLNO = $_POST['rollno'];
    $NAME = $_POST['name'];
    $PHNO = $_POST['phno'];
    $DEPARTMENT = $_POST['department'];
    $SEMESTER = $_POST['semester'];

    $imagePath = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $RAW_IMAGE = $_FILES['image']['name'];
        $SANITIZED_IMAGE = time() . '_' . preg_replace('/[^A-Za-z0-9.\-_]/', '_', $RAW_IMAGE);
        $tempname = $_FILES['image']['tmp_name'];
        $folder = "../../dataimg/" . $SANITIZED_IMAGE; // Adjust path to your image storage directory

        // Create directory if it doesn't exist
        if (!is_dir("../../dataimg")) {
            mkdir("../../dataimg", 0777, true);
        }

        if (move_uploaded_file($tempname, $folder)) {
            $imagePath = $SANITIZED_IMAGE; 
        } else {
            $message = "Image upload failed. Error code: " . $_FILES['image']['error'];
            $messageType = "danger";
        }
    } else if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $message = "Image upload failed. Error code: " . $_FILES['image']['error'];
        $messageType = "danger";
    }

    if ($messageType !== "danger") {
        $sql = "INSERT INTO `student` (`admission_no`, `name`, `phno`, `department`, `semester`, `image`)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($db, $sql);

        if ($stmt === false) {
            $message = "Database error: Failed to prepare statement for student insertion.";
            $messageType = "danger";
            error_log("Failed to prepare statement (add_student.php insert): " . mysqli_error($db));
        } else {
            // Bind parameters: 'ssssss' -> 6 strings (or 'isssss' if admission_no is INT)
            mysqli_stmt_bind_param($stmt, 'ssssss', $ROLLNO, $NAME, $PHNO, $DEPARTMENT, $SEMESTER, $imagePath);
            $run = mysqli_stmt_execute($stmt);

            if ($run === true) {
                $message = "Student data added successfully!";
                $messageType = "success";
            } else {
                // Check for duplicate entry error specifically (e.g., for unique admission_no)
                if (mysqli_errno($db) == 1062) { // 1062 is typically the error code for duplicate entry
                    $message = "Failed to add student: Admission Number '" . htmlspecialchars($ROLLNO) . "' already exists.";
                } else {
                    $message = "Database insert failed: " . mysqli_error($db);
                }
                $messageType = "danger";
                error_log("Student insertion failed for ID: $ROLLNO. Error: " . mysqli_error($db));
            }
            mysqli_stmt_close($stmt);
        }
    }
}

include '../../includes/header.php'; 
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/student/admin/admindash.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/student/admin/student/view_student.php">Students</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Student</li>
                </ol>
            </nav>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-lg p-4 mb-4">
                <h3 class="text-center mb-4">Add New Student</h3>

                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Full Name" required
                            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" name="department" id="department" class="form-control" placeholder="Enter Department" required
                            value="<?php echo htmlspecialchars($_POST['department'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="text" name="semester" id="semester" class="form-control" placeholder="Enter Semester" required
                            value="<?php echo htmlspecialchars($_POST['semester'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="rollno" class="form-label">Admission No</label>
                        <input type="text" name="rollno" id="rollno" class="form-control" placeholder="Enter Admission No" required
                            value="<?php echo htmlspecialchars($_POST['rollno'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="phno" class="form-label">Contact</label>
                        <input type="number" name="phno" id="phno" class="form-control" pattern="\d{10}" placeholder="Enter Phone Number" required
                            value="<?php echo htmlspecialchars($_POST['phno'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Profile Image</label>
                        <input type="file" name="image" id="image" class="form-control" required>
                        <small class="form-text text-muted">Please select a profile image for the student.</small>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary w-100">Add Student</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>