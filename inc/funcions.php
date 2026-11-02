<?php
declare(strict_types=1);

/**
 * Busca un usuario por su email.
 */
function buscaUsuari(array $usuaris, string $email): ?array {
    foreach ($usuaris as $u) {
        if ($u['email'] === $email) {
            return $u;
        }
    }
    return null;
}

/**
 * Retorna una representación legible del tiempo transcurrido desde un timestamp.
 */
function tempsDesDe(int $moment): string {
    $diferencia = time() - $moment;
    if ($diferencia < 60) {
        return "fa menys d'un minut";
    }
    
    $minuts = (int) floor($diferencia / 60);
    if ($minuts < 60) {
        return $minuts === 1 ? "fa 1 minut" : "fa {$minuts} minuts";
    }
    
    $hores = (int) floor($minuts / 60);
    if ($hores < 24) {
        return $hores === 1 ? "fa 1 hora" : "fa {$hores} hores";
    }
    
    $dies = (int) floor($hores / 24);
    return $dies === 1 ? "fa 1 dia" : "fa {$dies} dies";
}

/**
 * Comprueba si la votación está abierta comparando con la fecha límite.
 */
function votacioOberta(string $tancament): bool {
    $dataTancament = strtotime($tancament);
    if ($dataTancament === false) {
        return false;
    }
    return time() < $dataTancament;
}

/**
 * Comprueba si un email ya ha registrado un voto en el fichero JSON.
 */
function jaHaVotat(string $email): bool {
    $vots = llegeixVots();
    return isset($vots[$email]);
}

/**
 * Realiza el recuento de votos manteniendo todas las opciones.
 */
function recompte(array $vots, array $opcions): array {
    $resultat = [];
    foreach ($opcions as $clau => $text) {
        $resultat[$clau] = 0;
    }
    foreach ($vots as $opcio) {
        if (isset($resultat[$opcio])) {
            $resultat[$opcio]++;
        }
    }
    return $resultat;
}

/**
 * Guarda un mensaje flash en la sesión.
 */
function afegeixMissatge(string $text): void {
    $_SESSION['flash_msg'] = $text;
}

/**
 * Lee y elimina el mensaje flash de la sesión.
 */
function llegeixMissatge(): ?string {
    if (isset($_SESSION['flash_msg'])) {
        $msg = $_SESSION['flash_msg'];
        unset($_SESSION['flash_msg']);
        return $msg;
    }
    return null;
}