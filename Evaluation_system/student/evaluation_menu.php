<?php
include('../connection.php');
include('../session_detector.php');

$showPopup = true; // Flag to control the popup visibility

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agree'])) {
    $student_ID = $_SESSION['student_id'];
    $agreed = 'yes';

    // Check if the student has already agreed
    $checkQuery = "SELECT * FROM agree_list WHERE student_ID = '$student_ID'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        // Insert the agreement into the database
        $insertQuery = "INSERT INTO agree_list (student_ID, agreed) VALUES ('$student_ID', '$agreed')";
        mysqli_query($connection, $insertQuery);
    }

    // Set the flag to false to hide the popup
    $showPopup = false;

    // Redirect to the evaluation menu
    header("Location: evaluation_menu.php");
    exit();
}

// Check if the student has already agreed to hide the popup
if (isset($_SESSION['student_id'])) {
    $student_ID = $_SESSION['student_id'];
    $checkQuery = "SELECT * FROM agree_list WHERE student_ID = '$student_ID' AND agreed = 'yes'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $showPopup = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Evaluation Menu</title>
    <link rel="icon" href="../images/system-logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .evaluation-button {
            background-color: darkblue;
            min-width: 120px;
            margin-right: 0px;
            margin-left: -10px;
            padding-left: 15px;
            border-radius: 10px;
        }
        .evaluation-button:hover {
            background-color: #0056b3;
        }
        .button-closed {
            background-color: #a1a1a1;
            color: yellow;
            padding: 10px 10px;
            border: none;
            border-radius: 5px;
        }
        .button-pending {
            background-color: #ccc;
            color: blue;
            padding: 10px 10px;
            border: none;
            border-radius: 5px;
        }
        td {
            height: 50px !important;
        }
        .box-body {
            height: 68% !important;
        }
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .popup-content {
            width: 40%;
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: justify;
            justify-content: center;
        }
        .popup-buttons {
            margin-top: 20px;
        }
        .popup-buttons button {
            margin: 0 10px;
        }
    </style>
    <script>
        function handlePopup() {
            const checkbox = document.getElementById('agree-checkbox');
            const continueButton = document.getElementById('continue-button');

            checkbox.addEventListener('change', function() {
                continueButton.disabled = !this.checked;
            });

            document.getElementById('cancel-button').addEventListener('click', function() {
                window.location.href = 'student_dashboard.php';
            });

            document.getElementById('popup-form').addEventListener('submit', function(e) {
                if (checkbox.checked) {
                    document.getElementById('popup').style.display = 'none';
                }
            });
        }

        window.onload = handlePopup;
    </script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <?php if ($showPopup): ?>
        <div class="popup" id="popup">
            <div class="popup-content">
                <form method="POST" id="popup-form">
                    <div>
                        <h2>Faculty Evaluation Consent for Data Collection</h2>
                    <p>By participating in this faculty evaluation to all of this teachers conducted by Exact Colleges of 
Asia, I willingly provide my personal data,
understanding that the collection, processing, and 
interpretation of said data will adhere to the Data Privacy Act
of 2012 in the Philippines. I grant explicit consent for Exact 
Colleges of Asia to utilize the provided data
solely for the survey's specified purposes, ensuring its 
confidentiality and non-disclosure to third parties without my
explicit consent, except as required by law. I acknowledge my 
right to access, correct, or request the deletion of my data as
provided under the Data Privacy Act. This agreement signifies
my informed consent, understanding, and compliance with the 
aforementioned principles. <br><br><br></p>

                        <input type="checkbox" id="agree-checkbox" name="agree">
                        <label for="agree-checkbox">I Agree</label>
                    </div>
                    <div class="popup-buttons">
                        <button type="button" id="cancel-button">Cancel</button>
                        <button type="submit" id="continue-button" disabled>Continue</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
    <div class="content">
        <h1>EVALUATION MENU</h1><br><br><br><br>

        <?php
        if ($connection) {
            if (isset($_SESSION['student_id'])) {
                $student_ID = $_SESSION['student_id'];

                $query = "SELECT * FROM student_info WHERE student_ID = '$student_ID'";
                $result = mysqli_query($connection, $query);

                if (mysqli_num_rows($result) > 0) {
                    $student = mysqli_fetch_assoc($result);
                    $studentId = $student['id']; 
                } else {
                    echo "No student found with ID: $student_ID";
                    exit();
                }

                $academicYearStatusQuery = "SELECT id, year, semester, status FROM academic_list WHERE default_select = 1";
                $academicYearStatusResult = mysqli_query($connection, $academicYearStatusQuery);

                if (mysqli_num_rows($academicYearStatusResult) > 0) {
                    $academicYearStatusRow = mysqli_fetch_assoc($academicYearStatusResult);
                    $academicYearStatus = $academicYearStatusRow['status'];
                    $academicYearId = $academicYearStatusRow['id'];
                    $academicYear = $academicYearStatusRow['year'];
                    $semester = $academicYearStatusRow['semester'];
                } else {
                    echo "<div style='margin: 20px; padding: 20px; border: 1px solid red; background-color: #fdd;'>
                            <strong>No default academic year is selected by the admin.</strong><br>
                            No teachers can be evaluated. Please coordinate with your instructor for more details.
                          </div>";
                    exit();
                }

                $enrolledSubjectsQuery = "SELECT subjects.subject_code as subject_code, subjects.Name as subject_name, facultymembers.Name as faculty_name, facultymembers.FacultyID as faculty_id 
                                          FROM enrollments 
                                          INNER JOIN subjects ON enrollments.subject_code = subjects.subject_code 
                                          INNER JOIN facultymembers ON subjects.FacultyID = facultymembers.FacultyID 
                                          WHERE enrollments.student_id = '$student_ID'
                                          AND enrollments.academic_year = '$academicYearId'";

                $enrolledSubjectsResult = mysqli_query($connection, $enrolledSubjectsQuery);
                $enrolledSubjects = [];

                if (mysqli_num_rows($enrolledSubjectsResult) > 0) {
                    while ($row = mysqli_fetch_assoc($enrolledSubjectsResult)) {
                        $facultyId = $row['faculty_id'];
                        $facultyName = $row['faculty_name'];
                        $subjectName = $row['subject_name'];
                        $subjectCode = $row['subject_code'];

                        $enrolledSubjects[] = [
                            'faculty_id' => $facultyId,
                            'faculty_name' => $facultyName,
                            'subject_name' => $subjectName,
                            'subject_code' => $subjectCode,
                            'status' => 'Pending'
                        ];
                    }
                } else {
                    echo "No subjects found for student ID: $student_ID.";
                    exit();
                }

               
                $totalEvaluations = count($enrolledSubjects);
                $completedEvaluations = 0;
                foreach ($enrolledSubjects as $data) {
                    $conn = mysqli_connect($host, $user, $pass, $dbname);
                    if (!$conn) {
                        echo "Failed to connect to the database.";
                        exit();
                    }

                    $facultyIdEscaped = mysqli_real_escape_string($conn, $data['faculty_id']);
                    $subjectCodeEscaped = mysqli_real_escape_string($conn, $data['subject_code']);
                    $evaluationStatusQuery = "SELECT status FROM evaluation_table 
                                              WHERE student_id = '$student_ID' 
                                              AND FacultyID = '$facultyIdEscaped'
                                              AND subject_code = '$subjectCodeEscaped'
                                              AND academic_year = '$academicYearId'";
                    $evaluationStatusResult = mysqli_query($conn, $evaluationStatusQuery);

                    if (!$evaluationStatusResult) {
                        echo "Error fetching evaluation status: " . mysqli_error($conn);
                        exit();
                    }

                    $evaluationStatusRow = mysqli_fetch_assoc($evaluationStatusResult);
                    $evaluationStatus = $evaluationStatusRow['status'] ?? 'Pending';
                    if ($evaluationStatus == 'Evaluated') {
                        $completedEvaluations++;
                    }

                    mysqli_close($conn);
                }
            } else {
                echo "Student ID not provided.";
                exit();
            }
        } else {
            echo "Failed to connect to the database.";
            exit();
        }
        ?>

        <div class="box-header">
            <h2>Teachers To Evaluate: (<?php echo $completedEvaluations; ?>/<?php echo $totalEvaluations; ?>)</h2>
            <?php if ($completedEvaluations == $totalEvaluations && $totalEvaluations > 0) : ?>
                <h3 style="color:blue">All teachers have been evaluated. You may now log out.<br> Thank you for your time and cooperation.</h3>
            <?php endif; ?>
        </div>
        <div class="box-body">
            <div class="enrolled-subjects">
                <table border="1">
                    <tr>
                        <th>Faculty Name</th>
                        <th>Subject</th>
                        <th>Evaluation Status</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($enrolledSubjects as $data) : ?>
                        <tr>
                            <td><?php echo $data['faculty_name']; ?></td>
                            <td><?php echo $data['subject_name']; ?></td>
                            <td>
                                <?php
                                $conn = mysqli_connect($host, $user, $pass, $dbname);
                                if (!$conn) {
                                    echo "Failed to connect to the database.";
                                    exit();
                                }

                                $facultyIdEscaped = mysqli_real_escape_string($conn, $data['faculty_id']);
                                $subjectCodeEscaped = mysqli_real_escape_string($conn, $data['subject_code']);
                                $evaluationStatusQuery = "SELECT status FROM evaluation_table 
                                                          WHERE student_id = '$student_ID' 
                                                          AND FacultyID = '$facultyIdEscaped'
                                                          AND subject_code = '$subjectCodeEscaped'
                                                          AND academic_year = '$academicYearId'";
                                $evaluationStatusResult = mysqli_query($conn, $evaluationStatusQuery);

                                if (!$evaluationStatusResult) {
                                    echo "Error fetching evaluation status: " . mysqli_error($conn);
                                    exit();
                                }

                                $evaluationStatusRow = mysqli_fetch_assoc($evaluationStatusResult);
                                $evaluationStatus = $evaluationStatusRow['status'] ?? 'Pending';
                                echo ($evaluationStatus == 'Evaluated') ? 'Evaluated' : 'Pending';

                                mysqli_close($conn);
                                ?>
                            </td>
                            <td>
                                <?php if ($academicYearStatus == 0 || $evaluationStatus == 'Evaluated') : ?>
                                    <button class="button-closed" disabled><?php echo $evaluationStatus == 'Evaluated' ? 'Evaluated' : 'Closed'; ?></button>
                                <?php elseif ($academicYearStatus == 1 && $evaluationStatus == 'Pending') : ?>
                                    <button class="button-pending" disabled>Pending</button>
                                <?php else : ?>
                                    <a href="evaluation.php?&facultyId=<?php echo $data['faculty_id']; ?>&subject_code=<?php echo $data['subject_code']; ?>&subject=<?php echo urlencode($data['subject_name']); ?>&faculty_name=<?php echo urlencode($data['faculty_name']); ?>&academic_year_id=<?php echo $academicYearId; ?>" class="button">Evaluate</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
