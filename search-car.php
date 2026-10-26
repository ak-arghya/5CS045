
<?php
include("db.php");

$make = trim($_GET['make'] ?? '');
$model = trim($_GET['model'] ?? '');
$fuel_type = $_GET['fuel_type'] ?? '';
$year = trim($_GET['year'] ?? '');

$sql = "SELECT * FROM cars WHERE status = 'approved'";

$types = "";
$values = [];

if ($make != "") {
    $sql .= " AND make LIKE ?";
    $types .= "s";
    $values[] = "%" . $make . "%";
}


if ($model != "") {
    $sql .= " AND model LIKE ?";
    $types .= "s";
    $values[] = "%" . $model . "%";
}

if ($fuel_type != "") {
    $sql .= " AND fuel_type = ?";
    $types .= "s";
    $values[] = $fuel_type;
}


if ($year != "") {
    if (!ctype_digit($year) ||
        (int)$year < 1886 ||
        (int)$year > 2100) {
        exit("Please enter a valid year.");
    }

    $sql .= " AND year = ?";
    $types .= "i";
    $values[] = (int)$year;
}

$sql .= " ORDER BY make, model";

$stmt = mysqli_prepare($mysqli, $sql);

if (!empty($values)) {
    mysqli_stmt_bind_param($stmt, $types, ...$values);
}

mysqli_stmt_execute($stmt);

$results = mysqli_stmt_get_result($stmt);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Search Cars</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container">

    <?php include("nav.php"); ?>

    <h1 class="my-4">Search Cars</h1>

    <form action="search-car.php" method="get" class="mb-4">

        <div class="row g-3">

            <div class="col-md-3">
                <label class="form-label">Make</label>
                <input type="text"
                       name="make"
                       class="form-control"
                       maxlength="50"
                       value="<?= htmlspecialchars($make, ENT_QUOTES, 'UTF-8') ?>"
                       placeholder="e.g. BMW">
            </div>

            <div class="col-md-3">
                <label class="form-label">Model</label>
                <input type="text"
                       name="model"
                       class="form-control"
                       maxlength="50"
                       value="<?= htmlspecialchars($model, ENT_QUOTES, 'UTF-8') ?>"
                       placeholder="e.g. 320d">
            </div>

            <div class="col-md-3">
                <label class="form-label">Fuel Type</label>

                <select name="fuel_type" class="form-select">
                    <option value="">All fuel types</option>

                    <?php foreach (['Petrol', 'Diesel', 'Hybrid', 'Electric'] as $fuel): ?>
                        <option value="<?= $fuel ?>"
                            <?= $fuel_type === $fuel ? 'selected' : '' ?>>
                            <?= $fuel ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Year</label>
                <input type="number"
                       name="year"
                       class="form-control"
                       min="1886"
                       max="2100"
                       value="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>"
                       placeholder="e.g. 2023">
            </div>

        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                Search Cars
            </button>

            <a href="search-car.php" class="btn btn-secondary">
                Clear Search
            </a>
        </div>

    </form>

    <h3>Search Results</h3>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">

        <?php while ($a_row = mysqli_fetch_assoc($results)): ?>

            <div class="col">
                <div class="card h-100 shadow-sm">

                    <?php if (!empty($a_row['image'])): ?>
                        <img
                            src="images/<?= htmlspecialchars(basename($a_row['image']), ENT_QUOTES, 'UTF-8') ?>"
                            class="card-img-top"
                            style="height: 200px; object-fit: cover;"
                            alt="Car image">
                    <?php endif; ?>

                    <div class="card-body">

                        <h5 class="card-title">
                            <?= htmlspecialchars($a_row['make']) ?>
                            <?= htmlspecialchars($a_row['model']) ?>
                        </h5>

                        <p class="card-text">
                            <strong>Year:</strong>
                            <?= htmlspecialchars($a_row['year']) ?>
                            <br>

                            <strong>Fuel:</strong>
                            <?= htmlspecialchars($a_row['fuel_type'] ?? '') ?>
                            <br>

                            <strong>Engine:</strong>
                            <?= htmlspecialchars($a_row['engine_size'] ?? '') ?>
                            <br>

                            <strong>Transmission:</strong>
                            <?= htmlspecialchars($a_row['transmission'] ?? '') ?>
                            <br>

                            <strong>Price:</strong>
                            £<?= number_format((float)($a_row['price'] ?? 0), 2) ?>
                        </p>

                    </div>
                </div>
            </div>

        <?php endwhile; ?>

    </div>

    <?php if (mysqli_num_rows($results) == 0): ?>
        <p class="alert alert-warning mt-3">
            No cars found matching your search.
        </p>
    <?php endif; ?>

</div>
</body>
</html>
