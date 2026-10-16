
<?php

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

if (isset($_FILES['CarImage']) &&
    $_FILES['CarImage']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['CarImage']['error'] !== UPLOAD_ERR_OK) {
        exit("Image upload failed.");
    }

    // Check image size (maximum 5MB)
    if ($_FILES['CarImage']['size'] > 5 * 1024 * 1024) {
        exit("Image must be smaller than 5MB.");
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

    // Generate a unique image filename
    $image_name = bin2hex(random_bytes(16)) .
                  "." . $allowed_types[$file_type];

    // Save image in images folder
    $upload_folder = __DIR__ . "/images/";

    if (!is_dir($upload_folder)) {
        exit("Images folder does not exist.");
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
         transmission, price, description, image, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";

// Prepare SQL statement
$stmt = mysqli_prepare($mysqli, $sql);

if (!$stmt) {
    exit("Unable to prepare database query.");
}

// Add form values to the SQL statement
mysqli_stmt_bind_param(
    $stmt,
    "ssisssdss",
    $car_make,
    $car_model,
    $car_year,
    $fuel_type,
    $engine_size,
    $transmission,
    $car_price,
    $car_description,
    $image_name
);

// Run SQL statement
if (!mysqli_stmt_execute($stmt)) {
    if ($image_name !== null) {
        unlink(__DIR__ . "/images/" . $image_name);
    }
    exit("Unable to save car information.");
}

// Redirect to car list
header("Location: project.php");
exit;
