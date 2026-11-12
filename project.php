
<?php
// Start session and load security functions
include_once("session.php");

// Connect to database
include("db.php");

// Run SQL query - display all cars, newest first
$sql = "SELECT * FROM cars ORDER BY car_id DESC";

$results = mysqli_query($mysqli, $sql);

if (!$results) {
    exit("Unable to load cars.");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>5CS045 - Car Review</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Car card design */
        .card {
            border-radius: 12px;
            overflow: hidden;
            transition: 0.3s;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* Same image size for all cars */
        .card-img-top {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .car-image-placeholder {
            height: 200px;
            background-color: #eeeeee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777777;
        }

        /* Card content */
        .card-body {
            display: flex;
            flex-direction: column;
            padding: 18px;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            min-height: 48px;
        }

        .page-title {
            font-weight: bold;
        }

        /* Car description preview */
        .car-description-preview {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            line-clamp: 3;
            overflow: hidden;
            line-height: 1.5;
        }

        /* See More section */
        .car-description-details {
            margin-bottom: 15px;
        }

        .car-description-details summary {
            cursor: pointer;
            color: #0d6efd;
            font-weight: 600;
            list-style: none;
        }

        .car-description-details summary::-webkit-details-marker {
            display: none;
        }

        .car-description-details summary:hover {
            text-decoration: underline;
        }

        .car-description-details[open]
        .car-description-preview {
            display: none;
        }

        .car-description-details:not([open])
        .see-less {
            display: none;
        }

        .car-description-details[open]
        .see-more {
            display: none;
        }

        .full-description {
            line-height: 1.6;
            margin-top: 10px;
        }

        /* Keep Edit button near bottom */
        .edit-car-btn {
            margin-top: auto;
        }
    </style>
</head>

<body>
<div class="container">

    <?php include("nav.php"); ?>

    <!-- Page heading and buttons -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 my-4">

        <h1 class="page-title mb-0">
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

    <!-- Success message after editing -->
    <?php if (isset($_GET['updated']) && $_GET['updated'] === '1'): ?>

        <div class="alert alert-success">
            Car information updated successfully!
        </div>

    <?php endif; ?>


    <!-- Success message after deleting -->
    <?php if (
        isset($_GET['deleted']) &&
        $_GET['deleted'] === '1'
    ): ?>

        <div class="alert alert-success">
            Car deleted successfully!
        </div>

    <?php endif; ?>


    <!-- Display cars -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        <?php while ($a_row = mysqli_fetch_assoc($results)): ?>

            <div class="col">

                <div class="card h-100 shadow-sm">

                    <!-- Car image -->
                    <?php if (!empty($a_row['image'])): ?>

                        <img
                            src="images/<?= e(basename($a_row['image'])) ?>"
                            class="card-img-top"
                            alt="<?= e($a_row['make'] . ' ' . $a_row['model']) ?>">

                    <?php else: ?>

                        <div class="car-image-placeholder">
                            No Image Available
                        </div>

                    <?php endif; ?>

                    <div class="card-body">

                        <!-- Car make and model -->
                        <h5 class="card-title">
                            <?= e($a_row['make']) ?>
                            <?= e($a_row['model']) ?>
                        </h5>

                        <!-- Car specifications -->
                        <p class="card-text">

                            <strong>Year:</strong>
                            <?= e($a_row['year']) ?>
                            <br>

                            <strong>Fuel Type:</strong>
                            <?= e($a_row['fuel_type']) ?>
                            <br>

                            <strong>Engine Size:</strong>
                            <?= e($a_row['engine_size']) ?>
                            <br>

                            <strong>Transmission:</strong>
                            <?= e($a_row['transmission']) ?>
                            <br>

                            <strong>Price:</strong>
                            £<?= number_format((float)($a_row['price'] ?? 0), 2) ?>

                        </p>

                        <!-- Car description -->
                        <?php
                        $description = $a_row['description'] ?? '';
                        ?>

                        <?php if (mb_strlen($description) > 130): ?>

                            <!-- Expandable description -->
                            <details class="car-description-details">

                                <summary>
                                    <span class="car-description-preview text-dark fw-normal mb-2">
                                        <?= e($description) ?>
                                    </span>

                                    <span class="see-more">
                                        See More
                                    </span>

                                    <span class="see-less">
                                        See Less
                                    </span>
                                </summary>

                                <p class="full-description">
                                    <?= nl2br(e($description)) ?>
                                </p>

                            </details>

                        <?php else: ?>

                            <!-- Short description -->
                            <p class="card-text">
                                <?= nl2br(e($description)) ?>
                            </p>

                        <?php endif; ?>

                        <!-- Only owner can see Edit Car -->
                        <?php if (
                            isset($_SESSION['user_id']) &&
                            $a_row['user_id'] !== null &&
                            (int)$_SESSION['user_id'] === (int)$a_row['user_id']
                        ): ?>

                            
                            <div class="d-flex gap-2 mt-auto">

                                <!-- Edit Car button -->
                                <a href="edit-car-form.php?id=<?= (int)$a_row['car_id'] ?>"
                                class="btn btn-warning flex-fill">
                                    Edit Car
                                </a>

                                <!-- Delete Car button -->
                                <a href="delete-car-form.php?id=<?= (int)$a_row['car_id'] ?>"
                                class="btn btn-danger flex-fill">
                                    Delete Car
                                </a>

                            </div>


                        <?php endif; ?>

                    </div>

                </div>

            </div>

        <?php endwhile; ?>

    </div>

    <!-- Message when there are no cars -->
    <?php if (mysqli_num_rows($results) == 0): ?>

        <div class="alert alert-info mt-4">
            No cars are currently available.
        </div>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
