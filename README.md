# TASCA-04: Votacions de l'institut

## Preguntes de reflexió

### 1. Per què guardem qui ha votat al servidor (al fitxer de vots) i no en una cookie `ha_votat`? Què podria fer un alumne si ho guardéssim en una cookie?
Guardem el vot al servidor perquè les cookies s'emmagatzemen al navegador de l'usuari i poden ser modificades o esborrades fàcilment. Si ho guardéssim en una cookie `ha_votat`, un alumne podria obrir les eines de desenvolupador (F12), eliminar la cookie i votar múltiples vegades de manera fraudulenta.

### 2. Què passa si un usuari canvia el valor de la cookie `darrera_visita` a les eines del navegador? Com ho heu controlat?
Si un usuari modifica la cookie `darrera_visita` posant un text, un `0` o una data futura, l'aplicació podria fallar o mostrar dades incoherents. Ho hem controlat comprovant amb `is_numeric()` que el valor sigui un timestamp vàlid, superior a zero i igual o inferior al temps actual (`time()`); en cas contrari, es tracta automàticament com una primera visita sense generar cap *warning*.

### 3. Quina diferència hi ha entre el timeout de 5 minuts de la sessió i els 30 dies de la cookie `darrera_visita`? On es guarda cada dada?
El timeout de 5 minuts limita la durada de la sessió de l'usuari per motius de seguretat i les seves dades s'emmagatzemen al servidor (identificat per l'ID de sessió). La cookie `darrera_visita` s'emmagatzema directament al navegador del client durant 30 dies i només serveix per recordar de manera persistent l'últim accés.

### 4. A `index.php`, per què no podem mostrar l'hora que acabem de guardar a `darrera_visita` llegint `$_COOKIE['darrera_visita']` a la mateixa petició?
Perquè el superglobal `$_COOKIE` només conté les cookies que el navegador ha enviat al servidor en la petició HTTP actual. Quan fem servir `setcookie()`, la cookie s'envia a la capçalera de la resposta cap al client, per la qual cosa `$_COOKIE['darrera_visita']` no s'actualitzarà al servidor fins a la següent petició.