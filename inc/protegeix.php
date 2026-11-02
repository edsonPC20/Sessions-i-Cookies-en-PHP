<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Verificar si hay usuario en la sesión
if (!isset($_SESSION['usuari'])) {
    header('Location: index.php?error=acces');
    exit;
}

// 2. Control de timeout (5 minutos = 300 segundos)
$limiteInactivitat = 300;
$ara = time();

if (isset($_SESSION['ultima_activitat']) && ($ara - $_SESSION['ultima_activitat']) > $limiteInactivitat) {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header('Location: index.php?error=caducada');
    exit;
}

$_SESSION['ultima_activitat'] = $ara;