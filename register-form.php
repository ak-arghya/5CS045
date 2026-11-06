
<?php
include("session.php");

// Redirect users who are already logged in
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
    <title>Register - AutoReview UK</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">

    <?php include("nav.php"); ?>

    <div class="row justify-content-center my-5">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h1>Create Account</h1>
                    <p class="text-muted">
                        Register to contribute car information.
                    </p>

                    <form action="register.php" method="post">

                        <input type="hidden"
                               name="csrf_token"
                               value="<?= e($_SESSION['csrf_token']) ?>">

                        <div class="mb-3">
                            <label for="Name" class="form-label">
                                Full Name
                            </label>

                            <input type="text"
                                   class="form-control"
                                   id="Name"
                                   name="Name"
                                   maxlength="100"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="Email" class="form-label">
                                Email Address
                            </label>

                            <input type="email"
                                   class="form-control"
                                   id="Email"
                                   name="Email"
                                   maxlength="150"
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
                                   minlength="12"
                                   autocomplete="new-password"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="ConfirmPassword" class="form-label">
                                Confirm Password
                            </label>

                            <input type="password"
                                   class="form-control"
                                   id="ConfirmPassword"
                                   name="ConfirmPassword"
                                   minlength="12"
                                   autocomplete="new-password"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Create Account
                        </button>

                    </form>

                    <p class="mt-3 text-center">
                        Already have an account?
                        <a href="login-form.php">Login here</a>
                    </p>

                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
