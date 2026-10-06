<?php
// student/generate_marksheet_page.php
require_once '../../includes/auth.php'; // Adjust path if needed
require_once '../../includes/functions.php'; // Adjust path if needed

// Ensure student_id and mark_id are provided
$studentId = $_GET['student_id'] ?? '';
$examName = $_GET['exam_name'] ?? ''; // Pass exam name to find the specific mark

$student = null;
$selectedMark = null;

if (!empty($studentId) && !empty($examName)) {
    $students = loadStudents();
    $studentMarks = loadMarks($studentId); // Assuming loadMarks can load all marks for a student

    // Find the student
    foreach ($students as $s) {
        if ($s['admission_no'] === $studentId) {
            $student = $s;
            break;
        }
    }

    // Find the specific mark and calculate its details
    if ($student) {
        foreach ($studentMarks as $mark) {
            if ($mark['exam_name'] === $examName) { // Match by exam name
                // Ensure all subject marks are treated as numbers for calculation
                $english = (int)($mark['english'] ?? 0);
                $sec_language = (int)($mark['sec_language'] ?? 0);
                $maths = (int)($mark['maths'] ?? 0);
                $php = (int)($mark['php'] ?? 0);
                $dbms = (int)($mark['dbms'] ?? 0);
                $java = (int)($mark['java'] ?? 0);

                $mark['total'] = $english + $sec_language + $maths + $php + $dbms + $java;
                $mark['percentage'] = round(($mark['total'] / 600) * 100, 2); // Assuming 6 subjects, 100 marks each = 600 total
                
                // Simple grade calculation (you can define your own logic)
                if ($mark['percentage'] >= 90) { $mark['grade'] = 'A+'; }
                elseif ($mark['percentage'] >= 80) { $mark['grade'] = 'A'; }
                elseif ($mark['percentage'] >= 70) { $mark['grade'] = 'B'; }
                elseif ($mark['percentage'] >= 60) { $mark['grade'] = 'C'; }
                else { $mark['grade'] = 'F'; }

                $selectedMark = $mark;
                break; // Found the mark, no need to continue
            }
        }
    }
}

// If student or mark not found, display an error or redirect
if (!$student || !$selectedMark) {
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Error</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body>';
    echo '<div class="container mt-5 text-center"><div class="alert alert-danger" role="alert">Student or Marksheet not found.</div>';
    echo '<a href="view_student.php" class="btn btn-primary">Back to Students</a></div></body></html>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marksheet - <?php echo htmlspecialchars($selectedMark['exam_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            line-height: 1.6;
            background-color: #f8f9fa; /* Light background for consistency */
        }
        .marksheet-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        @media print {
            body {
                background-color: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .marksheet-container {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <div class="marksheet-container">
        <h2 class="text-center text-primary mb-4">Marksheet Details</h2>

        <div class="card mb-4">
            <div class="card-body bg-light rounded">
                <div class="row g-2">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Exam Name:</p>
                        <p class="fw-semibold text-dark"><?php echo htmlspecialchars($selectedMark['exam_name']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Student Name:</p>
                        <p class="fw-semibold text-dark"><?php echo htmlspecialchars($student['name'] ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Subject</th>
                        <th scope="col">Marks Obtained</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>English</td>
                        <td><?php echo htmlspecialchars($selectedMark['english']); ?></td>
                    </tr>
                    <tr>
                        <td>Second Language</td>
                        <td><?php echo htmlspecialchars($selectedMark['sec_language']); ?></td>
                    </tr>
                    <tr>
                        <td>Maths</td>
                        <td><?php echo htmlspecialchars($selectedMark['maths']); ?></td>
                    </tr>
                    <tr>
                        <td>PHP</td>
                        <td><?php echo htmlspecialchars($selectedMark['php']); ?></td>
                    </tr>
                    <tr>
                        <td>DBMS</td>
                        <td><?php echo htmlspecialchars($selectedMark['dbms']); ?></td>
                    </tr>
                    <tr>
                        <td>Java</td>
                        <td><?php echo htmlspecialchars($selectedMark['java']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row text-center g-3">
            <div class="col-md-4">
                <div class="card bg-info text-white shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlspecialchars($selectedMark['total']); ?></h4>
                        <small class="card-text">Total Marks</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlspecialchars($selectedMark['percentage']); ?>%</h4>
                        <small class="card-text">Percentage</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark shadow-sm h-100">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlspecialchars($selectedMark['grade']); ?></h4>
                        <small class="card-text">Grade</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Automatically trigger print dialog when the page loads
        window.onload = function() {
            window.print();
            // Optional: Close the window after printing (might be prevented by browser settings)
            // window.close();
        }
    </script>
</body>
</html>