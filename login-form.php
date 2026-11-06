
<?php
include("session.php");

if (isset($_SESSION['user_id'])) {
    header("Location: project.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - AutoReview UK</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">

    <?php include("nav.php"); ?>

    <div class="row justify-content-center my-5">
        <div class="col-md-5">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h1>Login</h1>

                    <?php if (isset($_GET['registered'])): ?>
                        <div class="alert alert-success">
                            Account created! Please log in.
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">
                            Invalid email or password.
                        </div>
                    <?php endif; ?>

                    <form action="login.php" method="post">

                        <input type="hidden"
                               name="csrf_token"
                               value="<?= e($_SESSION['csrf_token']) ?>">

                        <div class="mb-3">
                            <label for="Email" class="form-label">
                                Email Address
                            </label>

                            <input type="email"
                                   class="form-control"
                                   id="Email"
                                   name="Email"
                                   autocomplete="email"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="Password" class="form-label">
                                Password
                            </label>

                            <input type="password"
                                   class="form-control"
                                   id="Password"
                                   name="Password"
                                   autocomplete="current-password"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                    <p class="mt-3 text-center">
                        Don't have an account?
                        <a href="register-form.php">Register</a>
                    </p>

                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
