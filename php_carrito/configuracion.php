<?php
// Configurar ANTES de session_start()
ini_set('session.cookie_httponly', '1'); // HttpOnly
ini_set('session.cookie_secure', '1');   // Solo HTTPS
ini_set('session.cookie_samesite', 'Strict'); // Anti-CSRF
ini_set('session.use_strict_mode', '1'); // IDs regenerados
ini_set('session.gc_maxlifetime', '1800'); // 30 min timeout

session_start();

// Regenerar ID al iniciar sesion (previene Session Fixation)
if (!isset($_SESSION['iniciada'])) {
    session_regenerate_id(true);
    $_SESSION['iniciada'] = true;
}
?>
