
<?php

// Start session and load security functions
include_once("session.php");

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-form.php");
    exit;
}

// Only accept form submissions
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: project.php");
    exit;
}

// Check security token
check_csrf();

// Read values from the form
$car_id = filter_input(INPUT_POST, 'CarID', FILTER_VALIDATE_INT);

$car_make = trim($_POST['CarMake'] ?? '');
$car_model = trim($_POST['CarModel'] ?? '');
$car_year = filter_input(INPUT_POST, 'CarYear', FILTER_VALIDATE_INT);
$fuel_type = $_POST['FuelType'] ?? '';
$engine_size = trim($_POST['EngineSize'] ?? '');
$transmission = $_POST['Transmission'] ?? '';

$car_price = ($_POST['CarPrice'] ?? '') === ''
    ? null
    : filter_input(INPUT_POST, 'CarPrice', FILTER_VALIDATE_FLOAT);

$car_description = trim($_POST['CarDescription'] ?? '');

// Get logged-in user's ID
$user_id = (int)$_SESSION['user_id'];

// Validate form values
if (
    !$car_id || $car_id < 1 ||
    $car_make === '' || strlen($car_make) > 50 ||
    $car_model === '' || strlen($car_model) > 50 ||
    !$car_year || $car_year < 1886 || $car_year > 2100 ||
    !in_array($fuel_type, ['Petrol', 'Diesel', 'Hybrid', 'Electric'], true) ||
    !in_array($transmission, ['', 'Manual', 'Automatic'], true) ||
    strlen($engine_size) > 20 ||
    ($car_price !== null && ($car_price === false || $car_price < 0))
) {
    exit("Invalid car information. Please go back and check the form.");
}

// Connect to database
include("db.php");

// Check the car belongs to the logged-in user
$sql = "SELECT car_id FROM cars
        WHERE car_id = ? AND user_id = ?";

$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, "ii", $car_id, $user_id);
mysqli_stmt_execute($stmt);

$results = mysqli_stmt_get_result($stmt);
$car = mysqli_fetch_assoc($results);

mysqli_stmt_close($stmt);

if (!$car) {
    http_response_code(403);
    exit("You do not have permission to edit this car.");
}

// Build SQL UPDATE statement
$sql = "UPDATE cars
        SET make = ?,
            model = ?,
            year = ?,
            fuel_type = ?,
            engine_size = ?,
            transmission = ?,
            price = ?,
            description = ?
        WHERE car_id = ? AND user_id = ?";

// Prepare SQL statement
$stmt = mysqli_prepare($mysqli, $sql);

// Bind form values
mysqli_stmt_bind_param(
    $stmt,
    "ssisssdsii",
    $car_make,
    $car_model,
    $car_year,
    $fuel_type,
    $engine_size,
    $transmission,
    $car_price,
    $car_description,
    $car_id,
    $user_id
);

// Run SQL statement
if (!mysqli_stmt_execute($stmt)) {
    exit("Unable to update car information.");
}

// Close statement
mysqli_stmt_close($stmt);

// Redirect to homepage
header("Location: project.php?updated=1");
exit;

?>
