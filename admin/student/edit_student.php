<?php
// File: student/admin/student/edit_student.php

// Adjust paths as needed based on your project structure
require_once '../../includes/auth.php';     // For authentication and authorization
require_once '../../includes/functions.php'; // For loadStudents() and potentially other helpers
include '../../dbconnect.php';             // For database connection ($db variable)

// --- Authentication and Authorization Check ---
// It's crucial to ensure only authorized users can access this page.
// if (!isLoggedIn() || !isAdmin()) { // Assuming isLoggedIn() and isAdmin() are defined in auth.php
//     header('Location: ../../auth/login.php'); // Redirect to login page
//     exit('Access Denied: You do not have permission to edit students.');
// }
// session_start();
		if (isset($_SESSION['username'])) /*for security purpose without username and password deny access to dashboard*/ {
			echo "";
		} else {
			header('location:../login.php');
		}
// --- Fetch Student Data for Pre-filling the Form ---
$studentAdmissionNo = $_GET['id'] ?? ''; // Get student ID from URL
$studentToEdit = null;
$message = ''; 
$messageType = ''; 
if (!empty($studentAdmissionNo)) {
    $sql = "SELECT * FROM student WHERE admission_no = ?";
    $stmt = mysqli_prepare($db, $sql);

    if ($stmt === false) {
        $message = "Database error: Failed to prepare statement for fetching student.";
        $messageType = "danger";
        error_log("Failed to prepare statement (edit_student.php fetch): " . mysqli_error($db));
    } else {
        mysqli_stmt_bind_param($stmt, 's', $studentAdmissionNo);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $studentToEdit = mysqli_fetch_assoc($result);
        } else {
            $message = "Student not found with Admission No: " . htmlspecialchars($studentAdmissionNo);
            $messageType = "danger";
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $message = "No student ID provided for editing.";
    $messageType = "danger";
}

// --- Handle Form Submission for Updating Student Data ---
if (isset($_POST['submit'])) {
    // Get data from the form
    $ROLLNO_OLD = $_POST['old_rollno']; // Hidden field to identify the student being updated
    $ROLLNO = $_POST['rollno'];
    $NAME = $_POST['name'];
    $PHNO = $_POST['phno'];
    $DEPARTMENT = $_POST['department'];
    $SEMESTER = $_POST['semester'];

    $imagePath = $_POST['old_image'] ?? ''; // Keep old image path by default

    // Handle image upload if a new one is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $RAW_IMAGE = $_FILES['image']['name'];
        $SANITIZED_IMAGE = time() . '_' . preg_replace('/[^A-Za-z0-9.\-_]/', '_', $RAW_IMAGE);
        $tempname = $_FILES['image']['tmp_name'];
        $folder = "../../dataimg/" . $SANITIZED_IMAGE; // Adjust path to your image storage

        // Create directory if it doesn't exist
        if (!is_dir("../../dataimg")) {
            mkdir("../../dataimg", 0777, true);
        }

        if (move_uploaded_file($tempname, $folder)) {
            // New image uploaded successfully, update path
            $imagePath = $SANITIZED_IMAGE;

            // Optional: Delete old image file if it exists and is different
            if (!empty($_POST['old_image']) && $_POST['old_image'] !== $SANITIZED_IMAGE) {
                $oldImagePath = "../../dataimg/" . $_POST['old_image'];
                if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                    unlink($oldImagePath); // Delete the old file
                }
            }
        } else {
            $message = "Image upload failed: " . $_FILES['image']['error'];
            $messageType = "danger";
            // Do not proceed with DB update if image upload fails
            // You might want to skip this and use the old image path instead
            // For now, it will show an error and not update.
        }
    }

    // Only proceed with database update if no image upload error occurred
    if ($messageType !== "danger") {
        // Use a prepared statement for the UPDATE query (CRUCIAL FOR SECURITY)
        $sql = "UPDATE student SET name = ?, phno = ?, department = ?, semester = ?, image = ?, admission_no = ? WHERE admission_no = ?";
        $stmt = mysqli_prepare($db, $sql);

        if ($stmt === false) {
            $message = "Database error: Failed to prepare statement for update.";
            $messageType = "danger";
            error_log("Failed to prepare statement (edit_student.php update): " . mysqli_error($db));
        } else {
            mysqli_stmt_bind_param($stmt, 'sssssss', $NAME, $PHNO, $DEPARTMENT, $SEMESTER, $imagePath, $ROLLNO, $ROLLNO_OLD);
            $run = mysqli_stmt_execute($stmt);

            if ($run === true) {
                $message = "Student data updated successfully!";
                $messageType = "success";
                $studentAdmissionNo = $ROLLNO; // Update the ID to fetch the new data
                $sql = "SELECT * FROM student WHERE admission_no = ?";
                $stmt_fetch = mysqli_prepare($db, $sql);
                mysqli_stmt_bind_param($stmt_fetch, 's', $studentAdmissionNo);
                mysqli_stmt_execute($stmt_fetch);
                $result_fetch = mysqli_stmt_get_result($stmt_fetch);
                $studentToEdit = mysqli_fetch_assoc($result_fetch);
                mysqli_stmt_close($stmt_fetch);

            } else {
                $message = "Database update failed: " . mysqli_error($db);
                $messageType = "danger";
                error_log("Student update failed for ID: $ROLLNO_OLD. Error: " . mysqli_error($db));
            }
            mysqli_stmt_close($stmt);
        }
    }
}

include '../../includes/header.php'; // Includes your Bootstrap header
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/student/admin/admindash.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/student/admin/student/view_student.php">Students</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
                </ol>
            </nav>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-lg p-4 mb-4">
                <h3 class="text-center mb-4">Edit Student Details</h3>

                <?php if ($studentToEdit): ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="old_rollno" value="<?php echo htmlspecialchars($studentToEdit['admission_no']); ?>">
                    <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($studentToEdit['image'] ?? ''); ?>">

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Full Name" value="<?php echo htmlspecialchars($studentToEdit['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" name="department" id="department" class="form-control" placeholder="Enter Department" value="<?php echo htmlspecialchars($studentToEdit['department']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="text" name="semester" id="semester" class="form-control" placeholder="Enter Semester" value="<?php echo htmlspecialchars($studentToEdit['semester']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="rollno" class="form-label">Admission No</label>
                        <input type="text" name="rollno" id="rollno" class="form-control" placeholder="Enter Admission No" value="<?php echo htmlspecialchars($studentToEdit['admission_no']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phno" class="form-label">Contact</label>
                        <input type="number" name="phno" id="phno" class="form-control" pattern="\d{10}" placeholder="Enter Phone Number" value="<?php echo htmlspecialchars($studentToEdit['phno']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Profile Image</label>
                        <?php if (!empty($studentToEdit['image'])): ?>
                            <div class="mb-2">
                                <img src="../../dataimg/<?php echo htmlspecialchars($studentToEdit['image']); ?>" alt="Current Profile Image" class="img-thumbnail" style="max-width: 150px; height: auto;">
                                <small class="text-muted d-block mt-1">Current Image</small>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" id="image" class="form-control">
                        <small class="form-text text-muted">Leave blank to keep current image.</small>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary w-100">Update Student</button>
                </form>
                <?php else: ?>
                    <div class="alert alert-warning text-center" role="alert">
                        <p class="mb-0">Please provide a valid student ID to edit.</p>
                        <a href="view_student.php" class="btn btn-primary mt-3">Back to Students</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>