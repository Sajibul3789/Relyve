<?php
session_start();
include '../configs/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: login_form.php?error=Email and password are required.");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, first_name, last_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Secure the session
            session_regenerate_id(true);

            $_SESSION['user_id']    = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name']  = $user['last_name'];
            $_SESSION['email']      = $email;

            header("Location: ../index.php");   // Change to your dashboard/home page
            exit();
        } else {
            header("Location: ../login_form.php?error=Incorrect password.");
            exit();
        }
    } else {
        header("Location: ../login_form.php?error=No account found with this email.");
        exit();
    }

    $stmt->close();
}

$conn->close();
header("Location: login_form.php");
exit();
?>