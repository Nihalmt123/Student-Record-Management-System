<?php
// File: student/admin/student/delete_student.php

// require_once '../../../includes/auth.php';
require_once '../../includes/functions.php';
session_start();
if (isset($_SESSION['username'])) /*for security purpose without username and password deny access to dashboard*/ {
	echo "";
} else {
	header('location:../login.php');
}
// Basic authentication and authorization check
// Make sure isLoggedIn() and isAdmin() are defined in your auth.php or functions.php
// if (!isLoggedIn() || !isAdmin()) {
//     // Redirect unauthenticated/unauthorized users
//     header('Location: ../../auth/login.php'); // Adjust login page path if necessary
//     exit('Access Denied: You do not have permission to delete students.');
// }

// Get the student admission number from the URL
$studentAdmissionNo = $_GET['id'] ?? '';

if (empty($studentAdmissionNo)) {
    // Redirect if no ID is provided
    header('Location: view_student.php?status=error&message=' . urlencode('Error: Student ID not provided for deletion.'));
    exit();
}

// Call your deleteStudent function
// IMPORTANT: Your deleteStudent function currently echoes messages.
// For redirects, it's better if it returns true/false for success/failure.
// Let's modify it slightly below for better flow, or just capture its output.
// For now, we'll assume it prints and then we'll check if it returned anything indicating an error.

// Capture output from deleteStudent if it echoes directly
ob_start(); // Start output buffering
deleteStudent($studentAdmissionNo);
$deleteMessage = ob_get_clean(); // Get the echoed output

// Check the message to determine success/failure.
// A more robust deleteStudent would return true/false.
if (strpos($deleteMessage, "Successfully") !== false) {
    header('Location: view_student.php?status=success&message=' . urlencode('Student deleted successfully!'));
    exit();
} else {
    header('Location: view_student.php?status=error&message=' . urlencode('Failed to delete student: ' . $deleteMessage));
    exit();
}

?>