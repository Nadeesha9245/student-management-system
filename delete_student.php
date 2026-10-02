<?php

require_once "db.php";

if (!isset($_GET["id"])) {
    die("Student ID is missing.");
}

$id = $_GET["id"];

// First, find the related user ID
$sql = "SELECT user_id FROM students WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    die("Student not found.");
}

$user_id = $student["user_id"];

// Delete student record
$sql = "DELETE FROM students WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// Delete related user account
$sql = "DELETE FROM users WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);

if (mysqli_stmt_execute($stmt)) {

    header("Location: view_students.php");
    exit;

} else {

    echo "Delete failed: " . mysqli_error($conn);
}

?>