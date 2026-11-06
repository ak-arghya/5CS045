
<?php
// Ensure session has started
include_once("session.php");
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">

        <a class="navbar-brand" href="project.php">
            everythingaboutcars
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu"
                aria-controls="navbarMenu"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="project.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="search-car.php">
                        Search Cars
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="add-car-form.php">
                        Add a Car
                    </a>
                </li>

            </ul>

            <div class="d-flex align-items-center gap-2">

                <?php if (isset($_SESSION['user_id'])): ?>

                    <span class="navbar-text">
                        Hi, <?= e($_SESSION['name']) ?>
                    </span>

                    <form action="logout.php" method="post" class="m-0">

                        <input type="hidden"
                               name="csrf_token"
                               value="<?= e($_SESSION['csrf_token']) ?>">

                        <button type="submit" class="btn btn-danger">
                            Logout
                        </button>

                    </form>

                <?php else: ?>

                    <a href="register-form.php"
                       class="btn btn-outline-primary">
                        Register
                    </a>

                    <a href="login-form.php"
                       class="btn btn-primary">
                        Login
                    </a>

                <?php endif; ?>

            </div>

        </div>
    </div>
</nav>
