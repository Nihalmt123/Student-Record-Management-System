<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

$students = loadStudents();
// $marks = loadMarks();

// Calculate statistics
$totalStudents = count($students);
// $totalMarks = count($marks);

// Calculate average grade
// $avgGrade = 0;
// if ($totalMarks > 0) {
//     $totalGrades = array_sum(array_column($marks, 'grade'));
//     $avgGrade = round($totalGrades / $totalMarks, 2);
// }

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Dashboard</h2>
            <p class="text-muted">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <h2 class="card-text"><?php echo $totalStudents; ?></h2>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Marks</h5>
                    <h2 class="card-text"><?php echo $totalMarks; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Average Grade</h5>
                    <h2 class="card-text"><?php echo $avgGrade; ?>%</h2>
                </div>
            </div>
        </div> -->
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <h4>Quick Actions</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Add New Student</h5>
                    <p class="card-text">Register a new student in the system</p>
                    <a href="/student/admin/student/add_student.php" class="btn btn-primary">Add Student</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">View All Students</h5>
                    <p class="card-text">Browse and manage existing students</p>
                    <a href="student/view_student.php" class="btn btn-success">View Students</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Students -->
    <?php if (!empty($students)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <h4>Recent Students</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Department</th>
                                 <th>Semester</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recentStudents = array_slice(array_reverse($students), 0, 5);
                            foreach ($recentStudents as $student):
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['admission_no']); ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['phno']); ?></td>
                                    <td><?php echo htmlspecialchars($student['department']); ?></td>
                                    <td><?php echo htmlspecialchars($student['semester']); ?></td>
                                    <td>
                                        <a href= "/student/admin/student/student_profile.php?id=<?php echo $student['admission_no']; ?>"
                                            class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="/student/admin/student/add_marks.php?id=<?php echo $student['admission_no']; ?>"
                                            class="btn btn-sm btn-outline-success">Add Marks</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>


