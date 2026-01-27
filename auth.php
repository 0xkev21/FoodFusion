<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db/connect.php';

$auth_error = "";

function getRedirectUrl()
{
    return isset($_POST['redirect_to']) && !empty($_POST['redirect_to'])
        ? $_POST['redirect_to']
        : 'index.php';
}

function appendError($url, $error, $openMode)
{
    $urlParts = parse_url($url);
    $path = $urlParts['path'] ?? 'index.php';

    $queryParams = [];

    if (isset($urlParts['query'])) {
        parse_str($urlParts['query'], $queryParams);
    }

    $queryParams['error'] = $error;
    $queryParams['open'] = $openMode;

    return $path . '?' . http_build_query($queryParams);
}

// Register
if (isset($_POST['register_btn'])) {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm_password'];

    $redirectUrl = getRedirectUrl();

    if ($password !== $confirm) {
        header("Location: " . appendError($redirectUrl, "Passwords do not match", "register"));
        exit();
    }

    $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: " . appendError($redirectUrl, "Email already taken", "register"));
        exit();
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $insert = $con->prepare("INSERT INTO users (firstName, lastName, email, password)
                            VALUES (?, ?, ?, ?)");
    $insert->bind_param("ssss", $first_name, $last_name, $email, $hashed);

    if ($insert->execute()) {
        $_SESSION['user_id'] = $insert->insert_id;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;
        $_SESSION['created-at'] = date('Y-m-d H:i:s');

        header("Location: " . $redirectUrl);
        exit();
    } else {
        header("Location: " . appendError($redirectUrl, "Registration failed", "register"));
        exit();
    }
}

// login
if (isset($_POST['login_btn'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $redirectUrl = getRedirectUrl();

    // SESSION LOCKOUT CHECK
    $now = time();
    if (isset($_SESSION['lockout_until']) && $_SESSION['lockout_until'] > $now) {
        $secondsRemaining = $_SESSION['lockout_until'] - $now;
        $minutes = ceil($secondsRemaining / 60);
        header("Location: " . appendError($redirectUrl, "Too many attempts. Try again in $minutes mins.", "login"));
        exit();
    }

    $stmt = $con->prepare("SELECT id, firstName, lastName, email, password, createdAt FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // SUCCESS: Clear session attempts
            unset($_SESSION['login_attempts']);
            unset($_SESSION['lockout_until']);

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['first_name'] = $row['firstName'];
            $_SESSION['last_name'] = $row['lastName'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['created-at'] = $row['createdAt'];

            header("Location: " . $redirectUrl);
            exit();
        } else {
            // FAILURE: Increment session attempts
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;

            if ($_SESSION['login_attempts'] >= 3) {
                // Lock for 3 minutes
                $_SESSION['lockout_until'] = time() + (3 * 60);
                $errorMsg = "Account locked for 3 minutes due to 3 failed attempts.";
            } else {
                $remaining = 3 - $_SESSION['login_attempts'];
                $errorMsg = "Invalid password. $remaining attempts remaining.";
            }

            header("Location: " . appendError($redirectUrl, $errorMsg, "login"));
            exit();
        }
    } else {
        header("Location: " . appendError($redirectUrl, "User not found", "login"));
        exit();
    }
}

exit();
