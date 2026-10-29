<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>5CS045</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
        <?php 
        include("nav.php");?>
        <?php
        include("db.php");
        
        $sql = "SELECT * FROM cars";
        $results = mysqli_query($mysqli, $sql);
        ?>

        

        
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 my-4">

            <h1 class="mb-0">
                Explore cars and their specifications
            </h1>

            
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 my-4">

                <h1 class="mb-0">
                    Explore cars and their specifications
                </h1>

                <div>
                    <a href="search-car.php" class="btn btn-success">
                        Search Cars
                    </a>

                    <a href="add-car-form.php" class="btn btn-primary">
                        Add a Car
                    </a>
                </div>

            </div>


        </div>

        <div class="row row-cols-2 row-cols-md-4 row-cols-xl 10 g-4">
          <?php while($a_row = mysqli_fetch_assoc($results)):?>
            <div class="col">
              
              
            <div class="card h-100">

                
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

                    <p class="card-text">
                        <?= htmlspecialchars($a_row['description'] ?? '') ?>
                    </p>

                </div>

            </div>

            </div>
          <?php endwhile;?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>