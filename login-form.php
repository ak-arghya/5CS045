
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <?php include("nav.php"); ?>

    <h1 class="my-4">Login</h1>

    <form action="login.php" method="post">

        <div class="mb-3">
            <label for="Email" class="form-label">Email</label>
            <input type="email" class="form-control"
                   id="Email" name="Email" required>
        </div>

        <div class="mb-3">
            <label for="Password" class="form-label">Password</label>
            <input type="password" class="form-control"
                   id="Password" name="Password" required>
        </div>

        <input type="submit" class="btn btn-primary" value="Login">

    </form>
</div>
</body>
</html>
