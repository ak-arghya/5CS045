
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login-form.php");
    exit;
}

// Read form values
$email = trim($_POST['Email'] ?? '');
$password = $_POST['Password'] ?? '';

// Connect to database
include("db.php");

// Find user using their email
$sql = "SELECT user_id, name, password
        FROM users WHERE email = ?";

$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$results = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($results);

// Check password
if ($user && password_verify($password, $user['password'])) {

    // Prevent session fixation
    session_regenerate_id(true);

    // Store user information in session
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['name'] = $user['name'];

    header("Location: project.php");
    exit;
}

echo "Invalid email or password.";
?>
