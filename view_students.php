<?php

require_once "db.php";

$search = $_GET["search"] ?? "";

$sql = "SELECT students.id,
               students.student_id,
               users.full_name,
               users.email,
               students.course,
               students.phone
        FROM students
        INNER JOIN users ON students.user_id = users.id";

if ($search !== "") {
    $search_safe = mysqli_real_escape_string($conn, $search);

    $sql .= " WHERE students.student_id LIKE '%$search_safe%'
              OR users.full_name LIKE '%$search_safe%'
              OR users.email LIKE '%$search_safe%'
              OR students.course LIKE '%$search_safe%'";
}

$sql .= " ORDER BY students.id DESC";

$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>View Students</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/students.css">
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
                        <h1>All Students</h1>
                        <p>Manage registered students</p>
                    </div>

                   <div>

    <form method="GET" class="student-search">

        <input
            type="text"
            name="search"
            placeholder="Search student..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">
            Search
        </button>

    </form>

    <a href="admin_dashboard.php" class="back-btn">
        ← Dashboard
    </a>

    <a href="add_student.php" class="add-student-btn">
        + Add Student
    </a>

</div>

                </div>


                <!-- Students Table -->

                <div class="students-card">

                    <div class="students-table-wrapper">

                        <table>

                            <thead>

                                <tr>
                                    <th>Student ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
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

                                            <a
                                                href="edit_student.php?id=<?php echo $student["id"]; ?>"
                                                class="edit-btn">
                                                Edit
                                            </a>

                                            <a
                                                href="delete_student.php?id=<?php echo $student["id"]; ?>"
                                                class="delete-btn"
                                                onclick="return confirm('Are you sure you want to delete this student?');">
                                                Delete
                                            </a>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>

</body>
</html>