
<?php
// Configure session cookies before starting session
if (session_status() === PHP_SESSION_NONE) {

    ini_set('session.use_strict_mode', '1');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) &&
                    $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}

// Generate security token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check token when submitting forms
function check_csrf() {
    if (
        !isset($_POST['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        !hash_equals(
            $_SESSION['csrf_token'],
            $_POST['csrf_token']
        )
    ) {
        http_response_code(403);
        exit("Invalid form submission.");
    }
}

// Escape output to prevent XSS
function e($value) {
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}
?>
