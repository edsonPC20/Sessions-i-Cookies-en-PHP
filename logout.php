<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nomUsuari = $_SESSION['usuari']['nom'] ?? '';

// Vaciar sesión y borrar la cookie de sesión
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Sessió tancada</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem; }
    </style>
</head>
<body>
    <h2>Fins la propera, <?= htmlspecialchars($nomUsuari) ?>!</h2>
    <p><a href="index.php">Tornar a entrar</a></p>
</body>
</html>