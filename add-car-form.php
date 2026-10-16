
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add a car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">

        <?php include("nav.php"); ?>

        <h1>Add a car</h1>

        <form action="add-car.php" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="CarMake" class="form-label">Car make</label>
                <input type="text" class="form-control"
                       id="CarMake" name="CarMake" maxlength="50" required>
            </div>

            <div class="mb-3">
                <label for="CarModel" class="form-label">Car model</label>
                <input type="text" class="form-control"
                       id="CarModel" name="CarModel" maxlength="50" required>
            </div>

            <div class="mb-3">
                <label for="CarYear" class="form-label">Year</label>
                <input type="number" class="form-control"
                       id="CarYear" name="CarYear" min="1886" max="2100" required>
            </div>

            <div class="mb-3">
                <label for="FuelType" class="form-label">Fuel type</label>
                <select class="form-select" id="FuelType" name="FuelType" required>
                    <option value="">Select fuel type</option>
                    <option value="Petrol">Petrol</option>
                    <option value="Diesel">Diesel</option>
                    <option value="Hybrid">Hybrid</option>
                    <option value="Electric">Electric</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="EngineSize" class="form-label">Engine size</label>
                <input type="text" class="form-control"
                       id="EngineSize" name="EngineSize" maxlength="20">
            </div>

            <div class="mb-3">
                <label for="Transmission" class="form-label">Transmission</label>
                <select class="form-select" id="Transmission" name="Transmission">
                    <option value="">Select transmission</option>
                    <option value="Manual">Manual</option>
                    <option value="Automatic">Automatic</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="CarPrice" class="form-label">Price</label>
                <input type="number" class="form-control"
                       id="CarPrice" name="CarPrice" min="0" step="0.01">
            </div>

            <div class="mb-3">
                <label for="CarDescription" class="form-label">Description</label>
                <textarea class="form-control"
                          id="CarDescription" name="CarDescription"
                          rows="5"></textarea>
            </div>

            <div class="mb-3">
                <label for="CarImage" class="form-label">Upload Car Image</label>

                <input type="file"
                    class="form-control"
                    id="CarImage"
                    name="CarImage"
                    accept="image/jpeg,image/png,image/webp">

                <div class="form-text">
                    Upload a JPG, PNG or WebP image (maximum 5MB).
                </div>
            </div>


            <input type="submit" class="btn btn-primary" value="Add car">

        </form>
    </div>
</body>
</html>
