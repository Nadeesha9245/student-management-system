<?php

require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT id, full_name, email, password, role, status
            FROM users
            WHERE email = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {

        $user = mysqli_fetch_assoc($result);

        if (!password_verify($password, $user["password"])) {
            $message = "Invalid email or password.";

        } elseif ($user["status"] !== "approved") {
            $message = "Your account is not approved yet.";

        } else {

            session_start();

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["full_name"] = $user["full_name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] === "admin") {
                header("Location: admin_dashboard.php");
                exit;
            }

            header("Location: student_dashboard.php");
            exit;
        }

    } else {
        $message = "Invalid email or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <div class="login-page">

    <div class="login-card">

        <div class="login-header">
            <h1>Student Management</h1>
            <p>Welcome back! Please login to continue.</p>
        </div>

        <?php if ($message !== ""): ?>
            <div class="login-message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="login-form">

            <div class="form-group">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >
            </div>

            <div class="form-group">
                <label>Password</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Enter your password"
                        required
                    >

                    <button
                        type="button"
                        class="show-password"
                        onclick="togglePassword()">
                        👁
                    </button>
                </div>
            </div>

            <button type="submit" class="login-btn">
                Login
            </button>

        </form>

    </div>

</div>

<script>
function togglePassword() {

    const password = document.getElementById("password");

    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}
</script>

</body>
</html>