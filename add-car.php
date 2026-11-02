
<?php

// Start session to identify the logged-in user
session_start();

// Only accept form submissions
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: add-car-form.php");
    exit;
}

// Read values from the form
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

// Get the logged-in user's ID
// Anonymous visitors will have no owner
$user_id = $_SESSION['user_id'] ?? null;

// Validate form values
if (
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


// Upload car image
$image_name = null;

if (
    isset($_FILES['CarImage']) &&
    $_FILES['CarImage']['error'] !== UPLOAD_ERR_NO_FILE
) {

    if ($_FILES['CarImage']['error'] !== UPLOAD_ERR_OK) {
        exit("Image upload failed.");
    }

    // Check image size (maximum 5MB)
    if (
        $_FILES['CarImage']['size'] <= 0 ||
        $_FILES['CarImage']['size'] > 5 * 1024 * 1024
    ) {
        exit("Image must be between 1 byte and 5MB.");
    }

    // Check the actual file type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $file_type = $finfo->file($_FILES['CarImage']['tmp_name']);

    $allowed_types = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($allowed_types[$file_type])) {
        exit("Only JPG, PNG and WebP images are allowed.");
    }

    // Verify that the file is an image
    if (getimagesize($_FILES['CarImage']['tmp_name']) === false) {
        exit("Invalid image file.");
    }

    // Generate a unique image filename
    $image_name = bin2hex(random_bytes(16)) .
                  "." . $allowed_types[$file_type];

    // Save image in images folder
    $upload_folder = __DIR__ . "/images/";

    if (!is_dir($upload_folder)) {
        exit("Images folder does not exist.");
    }

    if (!is_writable($upload_folder)) {
        exit("Images folder is not writable.");
    }

    if (!move_uploaded_file(
        $_FILES['CarImage']['tmp_name'],
        $upload_folder . $image_name
    )) {
        exit("Unable to save image.");
    }
}


// Build SQL statement
$sql = "INSERT INTO cars
        (make, model, year, fuel_type, engine_size,
         transmission, price, description, image,
         user_id, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";

// Prepare SQL statement
$stmt = mysqli_prepare($mysqli, $sql);

if (!$stmt) {
    if ($image_name !== null) {
        unlink(__DIR__ . "/images/" . $image_name);
    }

    exit("Unable to prepare database query.");
}

// Add form values to the SQL statement
mysqli_stmt_bind_param(
    $stmt,
    "ssisssdssi",
    $car_make,
    $car_model,
    $car_year,
    $fuel_type,
    $engine_size,
    $transmission,
    $car_price,
    $car_description,
    $image_name,
    $user_id
);

// Run SQL statement
if (!mysqli_stmt_execute($stmt)) {

    // Remove uploaded image if database insertion fails
    if ($image_name !== null) {
        unlink(__DIR__ . "/images/" . $image_name);
    }

    exit("Unable to save car information.");
}

// Close statement
mysqli_stmt_close($stmt);

// Redirect to car list
header("Location: project.php");
exit;

?>
