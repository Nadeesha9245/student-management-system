<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}


require_once "db.php";

$sql = "SELECT COUNT(*) AS total_students FROM students";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

$total_students = $data["total_students"];

$sql = "SELECT COUNT(DISTINCT course) AS total_courses FROM students";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

$total_courses = $data["total_courses"];
$sql = "SELECT COUNT(*) AS recent_students
        FROM students
        WHERE registration_date >= CURDATE() - INTERVAL 7 DAY";

$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

$recent_students = $data["recent_students"];

$sql = "SELECT 
            students.student_id,
            users.full_name,
            users.email,
            students.course,
            students.phone,
            students.registration_date
        FROM students
        INNER JOIN users ON students.user_id = users.id
        ORDER BY students.registration_date DESC
        LIMIT 5";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>

   <div class="dashboard-layout">

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="logo">
            Student Management
        </div>

        <nav class="sidebar-menu">

            <a href="admin_dashboard.php" class="active">
                Dashboard
            </a>

            <a href="view_students.php">
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

        <header class="topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Student Management System</p>
            </div>

            <div class="admin-info">
                Admin
            </div>
        </header>


        <section class="welcome-section">

            <h2>
                Good morning,
                <?php echo htmlspecialchars($_SESSION["full_name"]); ?> 👋
            </h2>

            <p>
                Welcome back! Here's what's happening with your students.
            </p>

        </section>


        <!-- Dashboard Cards -->
        <section class="dashboard-cards">

            <div class="stat-card">
    <h3>Total Students</h3>
    <p><?php echo $total_students; ?></p>
</div>

            <div class="stat-card">
    <h3>Total Courses</h3>
    <p><?php echo $total_courses; ?></p>
</div>

            <div class="stat-card">
    <h3>Recent Students</h3>
    <p><?php echo $recent_students; ?></p>
</div>

        </section>

                <!-- Recent Students -->

        <section class="recent-students">

            <div class="section-header">
                <div>
                    <h2>Recent Students</h2>
                    <p>Recently registered students</p>
                </div>

                <form method="GET" action="view_students.php" class="dashboard-search">

    <input
        type="text"
        name="search"
        placeholder="Search student..."
    >

    <button type="submit">
        Search
    </button>

</form>

                <a href="view_students.php" class="view-all-btn">
                    View All
                </a>
            </div>

            <div class="table-container">

                <table class="students-table">

                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Phone</th>
                            <th>Registration Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($student = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td>
                                    <?php echo htmlspecialchars($student["student_id"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student["full_name"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student["email"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student["course"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student["phone"] ?? ""); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($student["registration_date"] ?? ""); ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </section>
    </main>

</div>
</body>
</html>