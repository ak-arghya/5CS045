
<?php
// Start session
include_once("session.php");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>5CS045 - Car Review</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container">

    <?php include("nav.php"); ?>

    <?php
    // Connect to database
    include("db.php");

    // Run SQL query - show approved cars
    $sql = "SELECT * FROM cars WHERE status = 'approved'";
    $results = mysqli_query($mysqli, $sql);

    if (!$results) {
        exit("Unable to load cars.");
    }
    ?>

    <!-- Page heading and buttons -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 my-4">

        <h1 class="mb-0">
            Explore cars and their specifications
        </h1>

        <div class="d-flex gap-2">

            <a href="search-car.php" class="btn btn-success">
                Search Cars
            </a>

            <a href="add-car-form.php" class="btn btn-primary">
                Add a Car
            </a>

        </div>

    </div>

    <!-- Display cars -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">

        <?php while($a_row = mysqli_fetch_assoc($results)): ?>

            <div class="col">

                <div class="card h-100">

                    <!-- Car image -->
                    <?php if (!empty($a_row['image'])): ?>

                        <img
                            src="images/<?= htmlspecialchars(basename($a_row['image']), ENT_QUOTES, 'UTF-8') ?>"
                            class="card-img-top"
                            style="height: 200px; object-fit: cover;"
                            alt="Car image">

                    <?php endif; ?>

                    <div class="card-body">

                        <!-- Car name -->
                        <h5 class="card-title">

                            <?= htmlspecialchars($a_row['make'], ENT_QUOTES, 'UTF-8') ?>
                            <?= htmlspecialchars($a_row['model'], ENT_QUOTES, 'UTF-8') ?>

                        </h5>

                        <!-- Car specifications -->
                        <p class="card-text">

                            <strong>Year:</strong>
                            <?= htmlspecialchars($a_row['year']) ?>
                            <br>

                            <strong>Fuel Type:</strong>
                            <?= htmlspecialchars($a_row['fuel_type'] ?? '') ?>
                            <br>

                            <strong>Engine Size:</strong>
                            <?= htmlspecialchars($a_row['engine_size'] ?? '') ?>
                            <br>

                            <strong>Transmission:</strong>
                            <?= htmlspecialchars($a_row['transmission'] ?? '') ?>
                            <br>

                            <strong>Price:</strong>
                            £<?= number_format((float)($a_row['price'] ?? 0), 2) ?>

                        </p>

                        <!-- Car description -->
                        <p class="card-text">
                            <?= nl2br(htmlspecialchars($a_row['description'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
                        </p>

                        <!-- Only the owner can edit this car -->
                        <?php if (
                            isset($_SESSION['user_id']) &&
                            $a_row['user_id'] !== null &&
                            (int)$_SESSION['user_id'] === (int)$a_row['user_id']
                        ): ?>

                            <a href="edit-car-form.php?id=<?= (int)$a_row['car_id'] ?>"
                               class="btn btn-warning">
                                Edit Car
                            </a>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        <?php endwhile; ?>

    </div>

    <?php if (mysqli_num_rows($results) == 0): ?>

        <div class="alert alert-info mt-4">
            No cars are currently available.
        </div>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
