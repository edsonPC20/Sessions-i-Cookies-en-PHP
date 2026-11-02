<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/protegeix.php';
require_once __DIR__ . '/inc/vots.php';
$dades = require_once __DIR__ . '/inc/dades.php';

require_once __DIR__ . '/inc/capcalera.php';

$usuari = $_SESSION['usuari'];
$loginTime = $_SESSION['login_time'] ?? time();
$oberta = votacioOberta($dades['consulta']['tancament']);
$haVotat = jaHaVotat($usuari['email']);
$dataTancamentFormat = date('d/m/Y \a \l\e\s H:i', strtotime($dades['consulta']['tancament']));
?>

<h2>Benvinguda</h2>
<p>Hola, <strong><?= htmlspecialchars($usuari['nom']) ?></strong>!</p>
<p>Has iniciat sessió <?= htmlspecialchars(tempsDesDe($loginTime)) ?>.</p>

<h3>Pregunta: <?= htmlspecialchars($dades['consulta']['pregunta']) ?></h3>
<p>Estat de la votació: <strong><?= $oberta ? "Oberta (fins el $dataTancamentFormat)" : "Tancada" ?></strong></p>

<div>
<?php if ($usuari['rol'] === 'votant'): ?>
    <?php if ($oberta && !$haVotat): ?>
        <p><a href="votacio.php"><strong>Votar ara</strong></a></p>
    <?php elseif ($haVotat): ?>
        <p>Ja has votat. <a href="resultats.php">Veure resultats</a></p>
    <?php else: ?>
        <p>La votació està tancada. <a href="resultats.php">Veure resultats</a></p>
    <?php endif; ?>
<?php else: ?>
    <p><a href="resultats.php">Veure resultats (Administració)</a></p>
<?php endif; ?>
</div>

<?php require_once __DIR__ . '/inc/peu.php'; ?>