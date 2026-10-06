<?php
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

// Load all students
$students = loadStudents();

// Get the search term if available
$search = $_GET['search'] ?? '';

// Filter students based on the search term
$filteredStudents = array_filter($students, function ($student) use ($search) {
    if (stripos($student['name'], $search) !== false) {
        return true;
    }
    if (stripos($student['admission_no'], $search) !== false) {
        return true;
    }
    if (stripos($student['phno'], $search) !== false) {
        return true;
    }
    if (stripos($student['department'], $search) !== false) {
        return true;
    }
    return false;
});

// Live search response (AJAX)
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    if (empty($filteredStudents)) {
        echo '<div class="alert alert-info text-center">No matching students found.</div>';
    } else {
        ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Semester</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filteredStudents as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['admission_no']); ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['department']); ?></td>
                                    <td><?php echo htmlspecialchars($student['semester']); ?></td>
                                    <td><?php echo htmlspecialchars($student['phno'] ?? '—'); ?></td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Student Actions">
                                            <a href="student_profile.php?id=<?php echo $student['admission_no']; ?>"
                                               class="btn btn-sm btn-outline-primary" title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit_student.php?id=<?php echo $student['admission_no']; ?>"
                                               class="btn btn-sm btn-outline-info" title="Edit Student">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_student.php?id=<?php echo $student['admission_no']; ?>"
                                               class="btn btn-sm btn-outline-danger" title="Delete Student"
                                               onclick="return confirm('Are you sure you want to delete this student record?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        Showing <?php echo count($filteredStudents); ?> student(s)
                        <?php if (!empty($search)): ?>
                            for search: "<?php echo htmlspecialchars($search); ?>"
                        <?php endif; ?>
                    </small>
                </div>
            </div>
        </div>
        <?php
    }
    exit;
}

include '../../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>All Students</h2>
                <a href="add_student.php" class="btn btn-primary">Add New Student</a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" class="d-flex" onsubmit="return false;">
                <input type="text" class="form-control me-2" name="search" id="searchInput"
                       placeholder="Search by name, ID, phone, or department..."
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-outline-primary">Search</button>
                <?php if (!empty($search)): ?>
                    <a href="view_student.php" class="btn btn-outline-secondary ms-2">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div id="resultsContainer">
        <?php if (empty($filteredStudents)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <?php if (!empty($search)): ?>
                            No students found matching your search criteria.
                        <?php else: ?>
                            No students have been added yet. <a href="add_student.php">Add your first student</a>.
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Semester</th>
                                            <th>Phone</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($filteredStudents as $student): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($student['admission_no']); ?></td>
                                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                                <td><?php echo htmlspecialchars($student['department']); ?></td>
                                                <td><?php echo htmlspecialchars($student['semester']); ?></td>
                                                <td><?php echo htmlspecialchars($student['phno'] ?? '—'); ?></td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Student Actions">
                                                        <a href="student_profile.php?id=<?php echo $student['admission_no']; ?>"
                                                           class="btn btn-sm btn-outline-primary" title="View Profile">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="edit_student.php?id=<?php echo $student['admission_no']; ?>"
                                                           class="btn btn-sm btn-outline-info" title="Edit Student">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="delete_student.php?id=<?php echo $student['admission_no']; ?>"
                                                           class="btn btn-sm btn-outline-danger" title="Delete Student"
                                                           onclick="return confirm('Are you sure you want to delete this student record?');">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <small class="text-muted">
                                    Showing <?php echo count($filteredStudents); ?> student(s)
                                    <?php if (!empty($search)): ?>
                                        for search: "<?php echo htmlspecialchars($search); ?>"
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const resultsContainer = document.getElementById('resultsContainer');

        function performLiveSearch(query) {
            fetch(`view_student.php?ajax=1&search=${encodeURIComponent(query)}`)
                .then(response => response.text())
                .then(html => {
                    resultsContainer.innerHTML = html;
                });
        }

        searchInput.addEventListener('input', function () {
            const searchTerm = this.value;
            performLiveSearch(searchTerm);
        });
    });
</script>

<?php include '../../includes/footer.php'; ?>
