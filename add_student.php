<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

require_once "db.php";

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $student_id = $_POST["student_id"];
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $course = $_POST["course"];
    $phone = $_POST["phone"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    $address = $_POST["address"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $sql = "INSERT INTO users (full_name, email, password, role, status)
            VALUES (?, ?, ?, 'student', 'approved')";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $full_name,
        $email,
        $hashedPassword
    );

    if (mysqli_stmt_execute($stmt)) {

        $user_id = mysqli_insert_id($conn);

        // Insert student
        $sql = "INSERT INTO students
                (student_id, user_id, course, phone, gender, dob, address)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sisssss",
            $student_id,
            $user_id,
            $course,
            $phone,
            $gender,
            $dob,
            $address
        );

        if (mysqli_stmt_execute($stmt)) {

            $success_message = "Student added successfully!";

        } else {

            $error_message = "Unable to add student.";

        }

    } else {

        $error_message = "Unable to create student account.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Student</title>

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

            <a href="view_students.php">
                Students
            </a>

            <a href="add_student.php" class="active">
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

                    <h1>Add New Student</h1>

                    <p>
                        Create a new student account
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


            <!-- Success Message -->

            <?php if ($success_message): ?>

                <div class="success-message">

                    <?php echo htmlspecialchars($success_message); ?>

                </div>

            <?php endif; ?>


            <!-- Error Message -->

            <?php if ($error_message): ?>

                <div class="error-message">

                    <?php echo htmlspecialchars($error_message); ?>

                </div>

            <?php endif; ?>


            <!-- Student Form -->

            <div class="students-card">

                <form method="POST" class="student-form">


                    <div class="form-section">

                        <h2>Student Information</h2>

                        <div class="form-grid">

                            <div class="form-group">

                                <label>Student ID</label>

                                <input
                                    type="text"
                                    name="student_id"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label>Full Name</label>

                                <input
                                    type="text"
                                    name="full_name"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label>Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label>Password</label>

                                <input
                                    type="password"
                                    name="password"
                                    required
                                >

                            </div>

                        </div>

                    </div>


                    <div class="form-section">

                        <h2>Course & Contact</h2>

                        <div class="form-grid">


                            <div class="form-group">

                                <label>Course</label>

                                <input
                                    type="text"
                                    name="course"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label>Phone</label>

                                <input
                                    type="text"
                                    name="phone"
                                >

                            </div>


                            <div class="form-group">

                                <label>Gender</label>

                                <select name="gender">

                                    <option value="">
                                        Select Gender
                                    </option>

                                    <option value="Male">
                                        Male
                                    </option>

                                    <option value="Female">
                                        Female
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>

                                </select>

                            </div>


                            <div class="form-group">

                                <label>Date of Birth</label>

                                <input
                                    type="date"
                                    name="dob"
                                >

                            </div>


                            <div class="form-group full-width">

                                <label>Address</label>

                                <textarea
                                    name="address"
                                    rows="4"
                                ></textarea>

                            </div>

                        </div>

                    </div>


                    <div class="form-actions">

                        <a
                            href="view_students.php"
                            class="cancel-btn">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="submit-btn">
                            Add Student
                        </button>

                    </div>


                </form>

            </div>

        </div>

    </main>

</div>

</body>

</html>