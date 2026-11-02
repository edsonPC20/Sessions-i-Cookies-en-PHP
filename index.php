<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/inc/funcions.php';
$dades = require_once __DIR__ . '/inc/dades.php';

// Si ya ha iniciado sesión, redirigir a bienvenida.php
if (isset($_SESSION['usuari'])) {
    header('Location: benvinguda.php');
    exit;
}

// Procesar login en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $contrasenya = $_POST['contrasenya'] ?? '';
    $recorda = isset($_POST['recorda']);

    $usuariEncontrat = null;
    if ($email) {
        $usuariEncontrat = buscaUsuari($dades['usuaris'], $email);
    }

    if ($usuariEncontrat && $usuariEncontrat['contrasenya'] === $contrasenya) {
        session_regenerate_id(true);
        $_SESSION['usuari'] = [
            'email' => $usuariEncontrat['email'],
            'nom'   => $usuariEncontrat['nom'],
            'rol'   => $usuariEncontrat['rol']
        ];
        $_SESSION['login_time'] = time();
        $_SESSION['ultima_activitat'] = time();

        // Gestionar Cookie "Recorda el meu email"
        if ($recorda) {
            setcookie('email_recordat', $usuariEncontrat['email'], [
                'expires'  => time() + (30 * 86400),
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        } else {
            setcookie('email_recordat', '', [
                'expires'  => time() - 3600,
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        header('Location: benvinguda.php');
        exit;
    } else {
        header('Location: index.php?error=credencials');
        exit;
    }
}

// Lectura y procesamiento de Cookie "darrera_visita"
$missatgeVisita = "Benvingut! És la teva primera visita.";
if (isset($_COOKIE['darrera_visita'])) {
    $val = $_COOKIE['darrera_visita'];
    if (is_numeric($val)) {
        $ts = (int)$val;
        if ($ts > 0 && $ts <= time()) {
            $missatgeVisita = "La teva darrera visita va ser " . tempsDesDe($ts) . ".";
        }
    }
}

// Actualizar cookie darrera_visita para el futuro
setcookie('darrera_visita', (string)time(), [
    'expires'  => time() + (30 * 86400),
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Cookie email_recordat
$emailRecordatVal = '';
$recordaChecked = false;
if (isset($_COOKIE['email_recordat'])) {
    $emailValidat = filter_var($_COOKIE['email_recordat'], FILTER_VALIDATE_EMAIL);
    if ($emailValidat !== false) {
        $emailRecordatVal = $emailValidat;
        $recordaChecked = true;
    }
}

// Errores por GET
$errorsMap = [
    'credencials' => 'Credencials no vàlides.',
    'acces'       => "Primer has d'iniciar sessió.",
    'caducada'    => 'La sessió ha caducat per inactivitat.'
];
$errorGet = $_GET['error'] ?? null;
$missatgeError = ($errorGet && isset($errorsMap[$errorGet])) ? $errorsMap[$errorGet] : null;
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Login - Votacions</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem; }
        .error { background: #ffebee; border-left: 4px solid #c62828; padding: 0.5rem 1rem; margin-bottom: 1rem; }
        .info { background: #e8f5e9; border-left: 4px solid #2e7d32; padding: 0.5rem 1rem; margin-bottom: 1rem; }
        .form-group { margin-bottom: 1rem; }
    </style>
</head>
<body>
    <h2>Iniciar sessió</h2>
    
    <div class="info"><?= htmlspecialchars($missatgeVisita) ?></div>

    <?php if ($missatgeError): ?>
        <div class="error"><?= htmlspecialchars($missatgeError) ?></div>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($emailRecordatVal) ?>" required>
        </div>
        <div class="form-group">
            <label for="contrasenya">Contrasenya:</label><br>
            <input type="password" id="contrasenya" name="contrasenya" required>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="recorda" value="1" <?= $recordaChecked ? 'checked' : '' ?>>
                Recorda el meu email
            </label>
        </div>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>