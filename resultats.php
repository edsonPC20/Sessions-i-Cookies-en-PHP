<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/protegeix.php';
require_once __DIR__ . '/inc/funcions.php'; // Asegura que las funciones estén disponibles
require_once __DIR__ . '/inc/vots.php';
$dades = require_once __DIR__ . '/inc/dades.php';

$usuari = $_SESSION['usuari'];
$oberta = votacioOberta($dades['consulta']['tancament']);
$haVotat = jaHaVotat($usuari['email']);
$isAdmin = ($usuari['rol'] === 'admin');

// 1. Control de permisos de acceso
if (!$isAdmin && $oberta && !$haVotat) {
    afegeixMissatge("Podràs veure els resultats quan hagis votat.");
    header('Location: votacio.php');
    exit;
}

// 2. Recuento y estadísticas
$vots = llegeixVots();
$recompteVots = recompte($vots, $dades['consulta']['opcions']);
$totalVotsEfectuats = array_sum($recompteVots);

// Calcular total de votantes registrados en el sistema
$totalVotantsPossibles = 0;
foreach ($dades['usuaris'] as $u) {
    if ($u['rol'] === 'votant') {
        $totalVotantsPossibles++;
    }
}

require_once __DIR__ . '/inc/capcalera.php';
?>

<h2>Resultats de la Votació</h2>
<p><strong><?= htmlspecialchars($dades['consulta']['pregunta']) ?></strong></p>

<table>
    <thead>
        <tr>
            <th>Opció</th>
            <th>Vots</th>
            <th>Percentatge</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dades['consulta']['opcions'] as $clau => $text): ?>
            <?php 
                $numVots = $recompteVots[$clau] ?? 0;
                $percentatge = ($totalVotsEfectuats > 0) ? (int)round(($numVots / $totalVotsEfectuats) * 100) : 0;
            ?>
            <tr>
                <td><?= htmlspecialchars($text) ?></td>
                <td><?= $numVots ?></td>
                <td><?= $percentatge ?>%</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><strong>Participació:</strong> <?= $totalVotsEfectuats ?> de <?= $totalVotantsPossibles ?> votants</p>

<?php require_once __DIR__ . '/inc/peu.php'; ?>