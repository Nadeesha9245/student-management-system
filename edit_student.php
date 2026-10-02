<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

require_once "db.php";

if (!isset($_GET["id"])) {
    die("Student ID is missing.");
}

$id = $_GET["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $student_id = $_POST["student_id"];
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $course = $_POST["course"];
    $phone = $_POST["phone"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    $address = $_POST["address"];

    $sql = "SELECT user_id FROM students WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $existingStudent = mysqli_fetch_assoc($result);

    if (!$existingStudent) {
        die("Student not found.");
    }

    $user_id = $existingStudent["user_id"];

    $sql = "UPDATE users
            SET full_name = ?, email = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssi",
        $full_name,
        $email,
        $user_id
    );

    mysqli_stmt_execute($stmt);

    $sql = "UPDATE students
            SET student_id = ?,
                course = ?,
                phone = ?,
                gender = ?,
                dob = ?,
                address = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssi",
        $student_id,
        $course,
        $phone,
        $gender,
        $dob,
        $address,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: view_students.php");
        exit;

    } else {

        echo "Update failed: " . mysqli_error($conn);
    }
}


$sql = "SELECT students.id,
               students.student_id,
               users.full_name,
               users.email,
               students.course,
               students.phone,
               students.gender,
               students.dob,
               students.address
        FROM students
        INNER JOIN users ON students.user_id = users.id
        WHERE students.id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$student = mysqli_fetch_assoc($result);

if (!$student) {
    die("Student not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Student</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/students.css">

</head>

<body>

<div class="dashboard-layout">

    <!-- Sidebar -->

    <aside class="sidebar">

        <div class="logo">
            Student Management
        </div>

        <nav class="sidebar-menu">

            <a href="admin_dashboard.php">
                Dashboard
            </a>

            <a href="view_students.php" class="active">
                Students
            </a>

            <a href="add_student.php">
                Add Student
            </a>

        </nav>

        <a href="logout.php" class="logout-link">
            Logout
        </a>

    </aside>


    <!-- Main Content -->

    <main class="main-content">

        <div class="students-page">

            <!-- Header -->

            <div class="students-header">

                <div>

                    <h1>Edit Student</h1>

                    <p>
                        Update student information
                    </p>

                </div>

                <div>

                    <a href="admin_dashboard.php" class="back-btn">
                        ← Dashboard
                    </a>

                    <a href="view_students.php" class="add-student-btn">
                        View Students
                    </a>

                </div>

            </div>


            <!-- Edit Form -->

            <div class="students-card">

                <form method="POST" class="student-form">


                    <!-- Student Information -->

                    <div class="form-section">

                        <h2>Student Information</h2>

                        <div class="form-grid">


                            <div class="form-group">

                                <label>Student ID</label>

                                <input
                                    type="text"
                                    name="student_id"
                                    value="<?php echo htmlspecialchars($student['student_id']); ?>"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label>Full Name</label>

                                <input
                                    type="text"
                                    name="full_name"
                                    value="<?php echo htmlspecialchars($student['full_name']); ?>"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label>Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    value="<?php echo htmlspecialchars($student['email']); ?>"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label>Course</label>

                                <input
                                    type="text"
                                    name="course"
                                    value="<?php echo htmlspecialchars($student['course']); ?>"
                                    required
                                >

                            </div>

                        </div>

                    </div>


                    <!-- Contact Information -->

                    <div class="form-section">

                        <h2>Contact & Personal Information</h2>

                        <div class="form-grid">


                            <div class="form-group">

                                <label>Phone</label>

                                <input
                                    type="text"
                                    name="phone"
                                    value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>"
                                >

                            </div>


                            <div class="form-group">

                                <label>Gender</label>

                                <select name="gender">

                                    <option value="">
                                        Select Gender
                                    </option>

                                    <option value="Male"
                                        <?php if ($student['gender'] === 'Male') echo 'selected'; ?>>
                                        Male
                                    </option>

                                    <option value="Female"
                                        <?php if ($student['gender'] === 'Female') echo 'selected'; ?>>
                                        Female
                                    </option>

                                    <option value="Other"
                                        <?php if ($student['gender'] === 'Other') echo 'selected'; ?>>
                                        Other
                                    </option>

                                </select>

                            </div>


                            <div class="form-group">

                                <label>Date of Birth</label>

                                <input
                                    type="date"
                                    name="dob"
                                    value="<?php echo htmlspecialchars($student['dob'] ?? ''); ?>"
                                >

                            </div>


                            <div class="form-group full-width">

                                <label>Address</label>

                                <textarea
                                    name="address"
                                    rows="4"
                                ><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>

                            </div>

                        </div>

                    </div>


                    <!-- Buttons -->

                    <div class="form-actions">

                        <a
                            href="view_students.php"
                            class="cancel-btn">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="submit-btn">
                            Update Student
                        </button>

                    </div>


                </form>

            </div>

        </div>

    </main>

</div>

</body>

</html>