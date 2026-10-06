<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Marksheet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align to top for better scrolling if content is long */
            min-height: 100vh;
            padding: 20px;
        }
        .marksheet-container {
            background-color: #ffffff;
            border-radius: 12px; /* Rounded corners */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); /* Soft shadow */
            padding: 30px;
            max-width: 800px;
            width: 100%;
            box-sizing: border-box; /* Include padding in element's total width and height */
        }
        /* Styles for printing */
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
                margin: 0;
                display: block;
                min-height: auto;
            }
            .marksheet-container {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
                max-width: 100%;
                width: 100%;
            }
            .print-button {
                display: none; /* Hide print button when printing */
            }
        }
    </style>
</head>
<body>

    <div id="marksheet-app" class="marksheet-container">
        </div>

    <script>
        // Sample mark data (replace with your actual data)
        const sampleMark = {
            exam_name: "Mid-Term Examination 2025",
            english: 85,
            sec_language: 78,
            maths: 92,
            php: 70,
            dbms: 88,
            java: 90,
            total: 503, // Assuming out of 600 (6 subjects * 100 marks)
            percentage: 83.83,
            grade: "A"
        };

        /**
         * Generates the HTML content for the marksheet based on the provided mark data.
         * @param {object} mark - An object containing the student's marks and details.
         * @returns {string} The HTML string for the marksheet.
         */
        function generateMarksheetHtml(mark) {
            return `
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8 uppercase">Student Marksheet</h2>

                <div class="bg-blue-50 p-6 rounded-lg mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600 text-sm">Exam Name:</p>
                            <p class="text-lg font-semibold text-gray-900">${mark.exam_name}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Student Name:</p>
                            <p class="text-lg font-semibold text-gray-900">John Doe</p> </div>
                    </div>
                </div>

                <div class="overflow-x-auto mb-8">
                    <table class="min-w-full bg-white rounded-lg shadow-sm">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200">
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider rounded-tl-lg">Subject</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider rounded-tr-lg">Marks Obtained</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-4 text-gray-800">English</td>
                                <td class="py-3 px-4 text-gray-800">${mark.english}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-4 text-gray-800">Second Language</td>
                                <td class="py-3 px-4 text-gray-800">${mark.sec_language}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-4 text-gray-800">Maths</td>
                                <td class="py-3 px-4 text-gray-800">${mark.maths}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-4 text-gray-800">PHP</td>
                                <td class="py-3 px-4 text-gray-800">${mark.php}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-4 text-gray-800">DBMS</td>
                                <td class="py-3 px-4 text-gray-800">${mark.dbms}</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-gray-800 rounded-bl-lg">Java</td>
                                <td class="py-3 px-4 text-gray-800 rounded-br-lg">${mark.java}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div class="bg-purple-50 p-6 rounded-lg shadow-md">
                        <h4 class="text-3xl font-bold text-purple-700">${mark.total}</h4>
                        <small class="text-purple-600 text-sm">Total Marks</small>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg shadow-md">
                        <h4 class="text-3xl font-bold text-green-700">${mark.percentage}%</h4>
                        <small class="text-green-600 text-sm">Overall Percentage</small>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg shadow-md">
                        <h4 class="text-3xl font-bold text-yellow-700">${mark.grade}</h4>
                        <small class="text-yellow-600 text-sm">Grade Achieved</small>
                    </div>
                </div>

                <div class="flex justify-center mt-8 print-button">
                    <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                        Print Marksheet
                    </button>
                </div>
            `;
        }

        /**
         * Displays the marksheet in the current container.
         * This function is the primary way to render the marksheet content.
         * @param {object} mark - The mark data to display.
         */
        function displayMarksheet(mark) {
            const marksheetContainer = document.getElementById('marksheet-app');
            if (marksheetContainer) {
                marksheetContainer.innerHTML = generateMarksheetHtml(mark);
            } else {
                console.error("Marksheet container not found. Cannot display marksheet.");
            }
        }

        // Call the display function with sample data when the page loads
        window.onload = function() {
            displayMarksheet(sampleMark);
        };
    </script>
</body>
</html>
