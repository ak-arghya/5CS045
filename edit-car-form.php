
<?php

// Start session and load security functions
include_once("session.php");

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-form.php");
    exit;
}

// Read car ID from URL
$car_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$car_id || $car_id < 1) {
    exit("Invalid car ID.");
}

// Connect to database
include("db.php");

// Find car belonging to the logged-in user
$sql = "SELECT * FROM cars
        WHERE car_id = ? AND user_id = ?";

// Prepare SQL statement
$stmt = mysqli_prepare($mysqli, $sql);

// Get logged-in user's ID
$user_id = (int)$_SESSION['user_id'];

// Bind values
mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $car_id,
    $user_id
);

// Run query
mysqli_stmt_execute($stmt);

// Get car information
$results = mysqli_stmt_get_result($stmt);
$car = mysqli_fetch_assoc($results);

mysqli_stmt_close($stmt);

// Check the car exists and belongs to user
if (!$car) {
    http_response_code(403);
    exit("Car not found or you do not have permission to edit it.");
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Car - AutoReview UK</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container">

    <?php include("nav.php"); ?>

    <h1 class="my-4">Edit Car</h1>

    <form action="edit-car.php" method="post">

        <!-- Security token -->
        <input type="hidden"
               name="csrf_token"
               value="<?= e($_SESSION['csrf_token']) ?>">

        <!-- Car ID -->
        <input type="hidden"
               name="CarID"
               value="<?= (int)$car['car_id'] ?>">

        <div class="mb-3">
            <label for="CarMake" class="form-label">Car Make</label>

            <input type="text"
                   class="form-control"
                   id="CarMake"
                   name="CarMake"
                   maxlength="50"
                   value="<?= e($car['make']) ?>"
                   required>
        </div>

        <div class="mb-3">
            <label for="CarModel" class="form-label">Car Model</label>

            <input type="text"
                   class="form-control"
                   id="CarModel"
                   name="CarModel"
                   maxlength="50"
                   value="<?= e($car['model']) ?>"
                   required>
        </div>

        <div class="mb-3">
            <label for="CarYear" class="form-label">Year</label>

            <input type="number"
                   class="form-control"
                   id="CarYear"
                   name="CarYear"
                   min="1886"
                   max="2100"
                   value="<?= e($car['year']) ?>"
                   required>
        </div>

        <div class="mb-3">
            <label for="FuelType" class="form-label">Fuel Type</label>

            <select class="form-select" id="FuelType" name="FuelType" required>
                <?php foreach (['Petrol', 'Diesel', 'Hybrid', 'Electric'] as $fuel): ?>
                    <option value="<?= e($fuel) ?>"
                        <?= $car['fuel_type'] === $fuel ? 'selected' : '' ?>>
                        <?= e($fuel) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="EngineSize" class="form-label">Engine Size</label>

            <input type="text"
                   class="form-control"
                   id="EngineSize"
                   name="EngineSize"
                   maxlength="20"
                   value="<?= e($car['engine_size']) ?>">
        </div>

        <div class="mb-3">
            <label for="Transmission" class="form-label">Transmission</label>

            <select class="form-select" id="Transmission" name="Transmission">
                <?php foreach (['', 'Manual', 'Automatic'] as $type): ?>
                    <option value="<?= e($type) ?>"
                        <?= ($car['transmission'] ?? '') === $type ? 'selected' : '' ?>>
                        <?= e($type === '' ? 'Select transmission' : $type) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="CarPrice" class="form-label">Price (£)</label>

            <input type="number"
                   class="form-control"
                   id="CarPrice"
                   name="CarPrice"
                   min="0"
                   step="0.01"
                   value="<?= e($car['price']) ?>">
        </div>

        <div class="mb-3">
            <label for="CarDescription" class="form-label">
                Description
            </label>

            <textarea class="form-control"
                      id="CarDescription"
                      name="CarDescription"
                      rows="5"><?= e($car['description']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Save Changes
        </button>

        <a href="project.php" class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
