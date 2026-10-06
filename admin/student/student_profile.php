<?php
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

$studentId = $_GET['id'] ?? '';
$student = null;
$studentMarks = [];

if (!empty($studentId)) {
    $students = loadStudents();
    $studentMarks = loadMarks($studentId);

    // Find the student
    foreach ($students as $s) {
        if ($s['admission_no'] === $studentId) {
            $student = $s;
            break;
        }
    }

    // Calculate total, percentage, and grade for each mark entry
    foreach ($studentMarks as &$mark) { // Use & to modify the original array elements
        $mark['total'] = $mark['english'] + $mark['sec_language'] + $mark['maths'] + $mark['php'] + $mark['dbms'] + $mark['java'];
        $mark['percentage'] = round(($mark['total'] / 600) * 100, 2); // Assuming 6 subjects, 100 marks each = 600 total
        // Simple grade calculation (you can define your own logic)
        if ($mark['percentage'] >= 90) {
            $mark['grade'] = 'A+';
        } elseif ($mark['percentage'] >= 80) {
            $mark['grade'] = 'A';
        } elseif ($mark['percentage'] >= 70) {
            $mark['grade'] = 'B';
        } elseif ($mark['percentage'] >= 60) {
            $mark['grade'] = 'C';
        } else {
            $mark['grade'] = 'F';
        }
    }
    unset($mark); // Break the reference to the last element
}

// Calculate overall average percentage for statistics
$avgPercentage = 0;
if (!empty($studentMarks)) {
    $totalPercentages = array_sum(array_column($studentMarks, 'percentage'));
    $avgPercentage = round($totalPercentages / count($studentMarks), 2);
}

include '../../includes/header.php'; // Assuming this includes necessary HTML head and body start
?>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
    }
    /* Custom styling for modal animations */
    .modal-overlay {
        transition: opacity 0.3s ease;
    }
    .modal-overlay.hidden {
        opacity: 0;
    }
    .modal-content {
        transition: transform 0.3s ease;
    }
    .modal-overlay.hidden .modal-content {
        transform: translateY(-20px);
    }

    /* Print specific styles for marksheet content */
    @media print {
        body {
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .marksheet-container {
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 20px !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        .print-button {
            display: none !important;
        }
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-wrap -mx-4">
        <div class="w-full">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="flex text-gray-700 text-sm">
                    <li class="breadcrumb-item mr-2"><a href="/student/admin/admindash.php" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
                    <li class="breadcrumb-item mr-2"><a href="view_student.php" class="text-blue-600 hover:text-blue-800">Students</a></li>
                    <li class="breadcrumb-item active text-gray-500"><?php echo htmlspecialchars($student['name'] ?? 'N/A'); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="flex flex-wrap -mx-4">
        <div class="w-full md:w-1/3 px-4 mb-6 md:mb-0">
            <div class="bg-white shadow-md rounded-lg">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <h5 class="text-lg font-semibold text-gray-800 mb-0">Student Information</h5>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                                                <!-- <img src="/student/dataimg/Capture.png" alt="Student Profile Picture" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-sm"> -->

                        <img src="/student/dataimg/<?php echo htmlspecialchars($student['image'] ?? 'N/A'); ?>" alt="Student Profile Picture" class="w-24 h-24 rounded-half object-cover border-4 border-white shadow-sm">
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($student['name'] ?? 'N/A'); ?></h3>
                            <p class="text-sm text-gray-500">Admission No: <?php echo htmlspecialchars($student['admission_no'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                    <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700">
                        <dt class="font-medium text-gray-600">Department:</dt>
                        <dd class="text-gray-900"><?php echo htmlspecialchars($student['department'] ?? 'N/A'); ?></dd>

                        <dt class="font-medium text-gray-600">Semester:</dt>
                        <dd class="text-gray-900"><?php echo htmlspecialchars($student['semester'] ?? 'N/A'); ?></dd>

                        <dt class="font-medium text-gray-600">Phone:</dt>
                        <dd class="text-gray-900"><?php echo htmlspecialchars($student['phno'] ?? '—'); ?></dd>
                    </dl>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg mt-6">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                    <h5 class="text-lg font-semibold text-gray-800 mb-0">Statistics</h5>
                </div>
                <div class="p-6 text-center">
                    <div class="flex justify-around items-center">
                        <div class="flex-1">
                            <h4 class="text-3xl font-bold text-blue-600"><?php echo count($studentMarks); ?></h4>
                            <small class="text-gray-500">Total Exams</small>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-3xl font-bold text-green-600"><?php echo $avgPercentage; ?>%</h4>
                            <small class="text-gray-500">Average Percentage</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-2/3 px-4">
            <div class="bg-white shadow-md rounded-lg">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-lg flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-gray-800 mb-0">Marks History</h5>
                    <a href="add_marks.php?id=<?php echo htmlspecialchars($student['admission_no'] ?? ''); ?>"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-3 rounded-md transition duration-200">
                        Add Marks
                    </a>
                </div>
                <div class="p-6">
                    <?php if (empty($studentMarks)): ?>
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-4">No marks recorded yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Mark</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($studentMarks as $mark): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($mark['exam_name']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($mark['total']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($mark['percentage']); ?>%</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($mark['grade']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="inline-flex space-x-2">
                                                    <button onclick='showMarksheet(<?= json_encode($mark) ?>);'
                                                        class="text-blue-600 hover:text-blue-900 p-2 rounded-full hover:bg-gray-100 transition duration-150 ease-in-out"
                                                        title="View Marksheet">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                    <button class="text-green-600 hover:text-green-900 p-2 rounded-full hover:bg-gray-100 transition duration-150 ease-in-out" onclick='downloadMarksheet(<?= json_encode($mark) ?>)'>
                                                        <i class="fas fa-download"></i> Download
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="customMarksheetModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center hidden z-50 modal-overlay">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-2xl w-full relative modal-content">
        <button onclick="hideCustomMarksheetModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl leading-none">
            &times;
        </button>

        <div id="marksheetModalContent" class="overflow-y-auto max-h-[80vh]">
            </div>

        <div class="mt-6 flex justify-end">
            <button onclick="hideCustomMarksheetModal()"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-md transition duration-300 ease-in-out mr-2">
                Close
            </button>
            <button onclick="printMarksheetFromModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300 ease-in-out">
                Print
            </button>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

<script>
    const customMarksheetModal = document.getElementById('customMarksheetModal');
    const marksheetModalContent = document.getElementById('marksheetModalContent');

    /**
     * Generates the HTML content for the marksheet based on the provided mark data.
     * @param {object} mark - An object containing the student's marks and details.
     * @returns {string} The HTML string for the marksheet.
     */
    function generateMarksheetHtml(mark) {
        return `
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6 uppercase">Student Marksheet</h2>

            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                    
                    <div>
                        <p class="text-gray-600">Student Name:</p>
                        <p class="text-base font-semibold text-gray-900"><?php echo htmlspecialchars($student['name'] ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600">Exam Name:</p>
                        <p class="text-base font-semibold text-gray-900">${mark.exam_name}</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto mb-6">
                <table class="min-w-full bg-white rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider rounded-tl-lg">Subject</th>
                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider rounded-tr-lg">Marks Obtained</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 px-4 text-gray-800">English</td>
                            <td class="py-2 px-4 text-gray-800">${mark.english}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 px-4 text-gray-800">Second Language</td>
                            <td class="py-2 px-4 text-gray-800">${mark.sec_language}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 px-4 text-gray-800">Maths</td>
                            <td class="py-2 px-4 text-gray-800">${mark.maths}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 px-4 text-gray-800">PHP</td>
                            <td class="py-2 px-4 text-gray-800">${mark.php}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 px-4 text-gray-800">DBMS</td>
                            <td class="py-2 px-4 text-gray-800">${mark.dbms}</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 text-gray-800 rounded-bl-lg">Java</td>
                            <td class="py-2 px-4 text-gray-800 rounded-br-lg">${mark.java}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div class="bg-purple-50 p-4 rounded-lg shadow-sm">
                    <h4 class="text-2xl font-bold text-purple-700">${mark.total}</h4>
                    <small class="text-purple-600 text-xs">Total Marks</small>
                </div>
                <div class="bg-green-50 p-4 rounded-lg shadow-sm">
                    <h4 class="text-2xl font-bold text-green-700">${mark.percentage}%</h4>
                    <small class="text-green-600 text-xs">Percentage</small>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg shadow-sm">
                    <h4 class="text-2xl font-bold text-yellow-700">${mark.grade}</h4>
                    <small class="text-yellow-600 text-xs">Grade</small>
                </div>
            </div>
        `;
    }

    /**
     * Displays the marksheet in a custom modal.
     * @param {object} mark - The mark data to display.
     */
    function showMarksheet(mark) {
        marksheetModalContent.innerHTML = generateMarksheetHtml(mark);
        customMarksheetModal.classList.remove('hidden');
        // Add animation classes
        customMarksheetModal.classList.add('opacity-100', 'translate-y-0');
    }

    /**
     * Hides the custom marksheet modal.
     */
    function hideCustomMarksheetModal() {
        // Add animation classes for disappearance
        customMarksheetModal.classList.remove('opacity-100', 'translate-y-0');
        customMarksheetModal.classList.add('opacity-0');
        setTimeout(() => {
            customMarksheetModal.classList.add('hidden');
            customMarksheetModal.classList.remove('opacity-0'); // Clean up
        }, 300); // Match transition duration
    }

    /**
     * Triggers printing of the marksheet displayed in the modal.
     */
    function printMarksheetFromModal() {
        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write('<html><head><title>Marksheet Print</title>');
        printWindow.document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
        printWindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: \'Inter\', sans-serif; padding: 20px; line-height: 1.6; }');
        printWindow.document.write('@media print { .print-button { display: none; } }');
        printWindow.document.write('<\/style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(marksheetModalContent.innerHTML); // Inject content from the modal
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.onload = function() {
            printWindow.print();
        };
    }

    /**
     * Handles the download action, opening a new window with the marksheet and triggering print.
     * @param {object} mark - The mark data to be downloaded/printed.
     */
    function downloadMarksheet(mark) {
        const win = window.open('', '', 'width=800,height=600');
        win.document.write(`
            <html>
                <head>
                    <title>Marksheet - ${mark.exam_name}</title>
                    <script src="https://cdn.tailwindcss.com"><\/script>
                    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
                    <style>
                        body { font-family: 'Inter', sans-serif; padding: 20px; line-height: 1.6; }
                        h2 { text-align: center; text-transform: uppercase; margin-bottom: 30px; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                        th, td { border: 1px solid #000; padding: 10px; text-align: left; }
                        th { background-color: #f0f0f0; font-weight: bold; }
                        /* Ensure Tailwind-like styling applies for print view */
                        .bg-blue-50 { background-color: #eff6ff; }
                        .p-4 { padding: 1rem; }
                        .rounded-lg { border-radius: 0.5rem; }
                        .mb-6 { margin-bottom: 1.5rem; }
                        .grid { display: grid; }
                        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
                        .md\\:grid-cols-2 { @media (min-width: 768px) { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
                        .gap-2 { gap: 0.5rem; }
                        .text-sm { font-size: 0.875rem; }
                        .text-base { font-size: 1rem; }
                        .font-semibold { font-weight: 600; }
                        .text-gray-600 { color: #4b5563; }
                        .text-gray-900 { color: #111827; }
                        .overflow-x-auto { overflow-x: auto; }
                        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
                        .divide-y > * + * { border-top-width: 1px; border-color: #e5e7eb; } /* For table rows */
                        .bg-gray-100 { background-color: #f3f4f6; }
                        .border-b { border-bottom-width: 1px; }
                        .border-gray-200 { border-color: #e5e7eb; }
                        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
                        .px-4 { padding-left: 1rem; padding-right: 1rem; }
                        .uppercase { text-transform: uppercase; }
                        .tracking-wider { letter-spacing: 0.05em; }
                        .text-left { text-align: left; }
                        .text-xs { font-size: 0.75rem; }
                        .font-medium { font-weight: 500; }
                        .text-center { text-align: center; }
                        .text-2xl { font-size: 1.5rem; }
                        .font-bold { font-weight: 700; }
                        .text-purple-700 { color: #6d28d9; }
                        .text-green-700 { color: #047857; }
                        .text-yellow-700 { color: #b45309; }
                        .text-purple-600 { color: #7c3aed; }
                        .text-green-600 { color: #059669; }
                        .text-yellow-600 { color: #ca8a04; }
                        .text-xs { font-size: 0.75rem; }
                        .bg-purple-50 { background-color: #f5f3ff; }
                        .bg-green-50 { background-color: #ecfdf5; }
                        .bg-yellow-50 { background-color: #fffbeb; }
                    </style>
                </head>
                <body>
                    <div class="marksheet-container">
                        ${generateMarksheetHtml(mark)}
                    </div>
                    <script>
                        // Automatically print when the new window loads
                        window.onload = function() {
                            window.print();
                        }
                    <\/script>
                </body>
            </html>
        `);
        win.document.close();
    }

    // Optional: Close modal when clicking outside of it
    customMarksheetModal.addEventListener('click', function(event) {
        if (event.target === customMarksheetModal) {
            hideCustomMarksheetModal();
        }
    });
</script>