<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/protegeix.php';
require_once __DIR__ . '/inc/funcions.php';
require_once __DIR__ . '/inc/vots.php';
$dades = require_once __DIR__ . '/inc/dades.php';

$usuari = $_SESSION['usuari'];
$oberta = votacioOberta($dades['consulta']['tancament']);
$haVotat = jaHaVotat($usuari['email']);

// 1. Comprobaciones de acceso según orden de requisitos
if ($usuari['rol'] !== 'votant') {
    afegeixMissatge("Només els votants poden votar.");
    header('Location: benvinguda.php');
    exit;
}

if (!$oberta) {
    afegeixMissatge("La votació està tancada.");
    header('Location: resultats.php');
    exit;
}

if ($haVotat) {
    afegeixMissatge("Ja has votat. Gràcies!");
    header('Location: resultats.php');
    exit;
}

$errorVot = null;

// 2. Procesar respuesta POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opcioTria = $_POST['opcio'] ?? '';
    
    if (array_key_exists($opcioTria, $dades['consulta']['opcions'])) {
        guardaVot($usuari['email'], $opcioTria);
        afegeixMissatge("Vot registrat. Gràcies per participar!");
        header('Location: resultats.php');
        exit;
    } else {
        $errorVot = "Tria una de les opcions.";
    }
}

require_once __DIR__ . '/inc/capcalera.php';
?>

<h2>Formulari de Votació</h2>
<p><strong><?= htmlspecialchars($dades['consulta']['pregunta']) ?></strong></p>

<?php if ($errorVot): ?>
    <div class="error"><?= htmlspecialchars($errorVot) ?></div>
<?php endif; ?>

<form action="votacio.php" method="POST">
    <?php foreach ($dades['consulta']['opcions'] as $clau => $text): ?>
        <div>
            <label>
                <input type="radio" name="opcio" value="<?= htmlspecialchars($clau) ?>" required>
                <?= htmlspecialchars($text) ?>
            </label>
        </div>
    <?php endforeach; ?>
    <br>
    <button type="submit">Enviar vot</button>
</form>

<?php require_once __DIR__ . '/inc/peu.php'; ?>