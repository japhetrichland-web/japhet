<?php
session_start();

// Journalisation de la déconnexion
$username = $_SESSION['admin_username'] ?? 'Inconnu';
$logout_time = date('Y-m-d H:i:s');

// Détruire toutes les données de session
$_SESSION = array();

// Si vous voulez détruire complètement la session, effacez également
// le cookie de session.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalement, détruire la session.
session_destroy();

// Message de déconnexion
$message = 'Déconnexion réussie';
if (isset($_GET['inactivity'])) {
    $message = 'Déconnexion automatique pour inactivité';
} elseif (isset($_GET['timeout'])) {
    $message = 'Session expirée, veuillez vous reconnecter';
}

// Rediriger vers la page de login avec message
header('Location: login.php?logout=1&message=' . urlencode($message));
exit;
?>