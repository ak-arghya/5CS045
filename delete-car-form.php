
<?php

// Start session and load security functions
include_once("session.php");

// Check user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-form.php");
    exit;
}

// Read car ID from URL
$car_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$car_id || $car_id < 1) {
    exit("Invalid car ID.");
}

// Get logged-in user ID
$user_id = (int)$_SESSION['user_id'];

// Connect to database
include("db.php");

// Find car belonging to user
$sql = "SELECT car_id, make, model
        FROM cars
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
mysqli_stmt_execute($stmt);

// Get car information
$results = mysqli_stmt_get_result($stmt);
$car = mysqli_fetch_assoc($results);

mysqli_stmt_close($stmt);

// Check ownership
if (!$car) {
    http_response_code(403);
    exit("Car not found or you do not have permission to delete it.");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Delete Car - AutoReview UK</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container">

    <?php include("nav.php"); ?>

    <div class="row justify-content-center my-5">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h1 class="mb-4">Delete Car</h1>

                    <div class="alert alert-warning">

                        Are you sure you want to delete

                        <strong>
                            <?= e($car['make']) ?>
                            <?= e($car['model']) ?>
                        </strong>?

                        <p class="mt-2 mb-0">
                            This action cannot be undone.
                        </p>

                    </div>

                    <!-- Delete confirmation form -->
                    <form action="delete-car.php" method="post">

                        <!-- Security token -->
                        <input type="hidden"
                               name="csrf_token"
                               value="<?= e($_SESSION['csrf_token']) ?>">

                        <!-- Selected car ID -->
                        <input type="hidden"
                               name="CarID"
                               value="<?= (int)$car['car_id'] ?>">

                        <button type="submit"
                                class="btn btn-danger">
                            Yes, Delete Car
                        </button>

                        <a href="project.php"
                           class="btn btn-secondary">
                            Cancel
                        </a>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
