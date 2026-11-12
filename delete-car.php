
<?php

// Start session and load security functions
include_once("session.php");

// Check user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-form.php");
    exit;
}

// Only accept form submissions
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed.");
}

// Check security token
check_csrf();

// Read car ID from form
$car_id = filter_input(INPUT_POST, 'CarID', FILTER_VALIDATE_INT);

// Get logged-in user's ID
$user_id = (int)$_SESSION['user_id'];

// Validate car ID
if (!$car_id || $car_id < 1) {
    exit("Invalid car ID.");
}

// Connect to database
include("db.php");

// Build SQL DELETE statement
$sql = "DELETE FROM cars
        WHERE car_id = ? AND user_id = ?";

// Prepare SQL statement
$stmt = mysqli_prepare($mysqli, $sql);

// Bind values
mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $car_id,
    $user_id
);

// Run SQL statement
if (!mysqli_stmt_execute($stmt)) {
    exit("Unable to delete car.");
}

// Check if a car was actually deleted
if (mysqli_stmt_affected_rows($stmt) !== 1) {
    mysqli_stmt_close($stmt);

    http_response_code(403);
    exit("Car not found or you do not have permission to delete it.");
}

// Close statement
mysqli_stmt_close($stmt);

// Redirect to homepage
header("Location: project.php?deleted=1");
exit;

?>
