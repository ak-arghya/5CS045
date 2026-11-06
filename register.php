
<?php
// Start session and load security functions
include("session.php");

// Only accept form submissions
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register-form.php");
    exit;
}

// Check security token
check_csrf();

// Read values from the form
$name = trim($_POST['Name'] ?? '');
$email = strtolower(trim($_POST['Email'] ?? ''));
$password = $_POST['Password'] ?? '';
$confirm_password = $_POST['ConfirmPassword'] ?? '';

// Validate form values
if ($name === '' || mb_strlen($name) > 100) {
    exit("Please enter a valid name.");
}

if (
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($email) > 150
) {
    exit("Please enter a valid email address.");
}

if (strlen($password) < 12) {
    exit("Password must contain at least 12 characters.");
}

if (strlen($password) > 72) {
    exit("Password is too long. Maximum 72 bytes for this form.");
}

if ($password !== $confirm_password) {
    exit("Passwords do not match.");
}

// Connect to database
include("db.php");

// Check whether email already exists
$sql = "SELECT user_id FROM users WHERE email = ?";

$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$results = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($results) > 0) {
    exit("This email address is already registered.");
}

mysqli_stmt_close($stmt);

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Build SQL statement
$sql = "INSERT INTO users (name, email, password)
        VALUES (?, ?, ?)";

// Prepare SQL statement
$stmt = mysqli_prepare($mysqli, $sql);

// Bind form values
mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $name,
    $email,
    $password_hash
);

// Run SQL statement
if (!mysqli_stmt_execute($stmt)) {
    exit("Unable to create account. Please try again.");
}

mysqli_stmt_close($stmt);

// Redirect to login
header("Location: login-form.php?registered=1");
exit;
?>
