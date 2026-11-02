<?php
declare(strict_types=1);

require_once __DIR__ . '/funcions.php';

$usuariActual = $_SESSION['usuari'] ?? null;
$msgFlash = llegeixMissatge();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Votacions de l'Institut</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem; line-height: 1.5; }
        nav { background: #f0f0f0; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; }
        nav a { margin-right: 1rem; }
        .flash { background: #e0f7fa; border-left: 4px solid #00838f; padding: 0.5rem 1rem; margin-bottom: 1rem; }
        .error { background: #ffebee; border-left: 4px solid #c62828; padding: 0.5rem 1rem; margin-bottom: 1rem; }
        table { border-collapse: collapse; width: 100%; max-width: 600px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
<header>
    <h1>Votacions de l'Institut</h1>
    <?php if ($usuariActual): ?>
        <nav>
            Hola, <strong><?= htmlspecialchars($usuariActual['nom']) ?></strong> | 
            <a href="benvinguda.php">Inici</a>
            <?php if ($usuariActual['rol'] === 'votant'): ?>
                <a href="votacio.php">Votar</a>
            <?php endif; ?>
            <a href="resultats.php">Resultats</a>
            <a href="logout.php">Tancar sessió</a>
        </nav>
    <?php endif; ?>
</header>
<main>
<?php if ($msgFlash): ?>
    <div class="flash"><?= htmlspecialchars($msgFlash) ?></div>
<?php endif; ?>