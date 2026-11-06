
<?php
// Start session
include("session.php");

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login-form.php");
    exit;
}

// Validate security token
check_csrf();

// Read values from the form
$email = strtolower(trim($_POST['Email'] ?? ''));
$password = $_POST['Password'] ?? '';

// Validate input
if (
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($email) > 150 ||
    $password === ''
) {
    header("Location: login-form.php?error=1");
    exit;
}

// Basic login attempt limit per session
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SESSION['login_attempts'] >= 5) {
    http_response_code(429);
    exit("Too many failed login attempts in this session.");
}

// Connect to database
include("db.php");

// Find user using their email
$sql = "SELECT user_id, name, password
        FROM users WHERE email = ?";

// Prepare SQL query
$stmt = mysqli_prepare($mysqli, $sql);

mysqli_stmt_bind_param($stmt, "s", $email);

// Run query
mysqli_stmt_execute($stmt);

// Get result
$results = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($results);

mysqli_stmt_close($stmt);

// Check email and password
if ($user && password_verify($password, $user['password'])) {

    // Prevent session fixation
    session_regenerate_id(true);

    // Store user information
    $_SESSION['user_id'] = (int)$user['user_id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['login_attempts'] = 0;

    // Redirect to homepage
    header("Location: project.php");
    exit;
}

// Count failed login
$_SESSION['login_attempts']++;

// Redirect to login with error
header("Location: login-form.php?error=1");
exit;
?>
