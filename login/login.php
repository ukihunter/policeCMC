<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $error = "Please fill all fields";
    } else {

        $sql = "SELECT * FROM users WHERE email = ? AND status = 'active'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["full_name"] = $user["full_name"];
                $_SESSION["position"] = $user["position"];
                $_SESSION["rank_title"] = $user["rank_title"];

                header("Location: ../Dashboard/dashboard.php");
                exit;
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "User not found or inactive";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police CMS - Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="logo-container">
            <div class="shield-icon">
                <img src="./../icons/gavel.png" alt="icon">
            </div>
            <h1>Police CMS</h1>
            <p class="subtitle">Log in to the system</p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <span class="input-icon"><img src="./../icons/mail.png" alt=""></span>
                    <input type="email" id="email" name="email" placeholder="mail@gmail.com" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <span class="input-icon"><img src="./../icons/padlock.png" alt=""></span>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="sign-in-btn">Log In</button>
        </form>

        <p class="footer-text">Authorized personnel only</p>
    </div>
    <footer class="footer-right">
        © Developed by uki
    </footer>

</body>


</html>