<?php
session_start();

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // En production, utiliser des identifiants sécurisés et hashés
    if ($username === 'japhet' && $password === 'Richland2006') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['login_time'] = time();
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Identifiants incorrects';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">ù
    <title>Admin Login - Portfolio Japhet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">Connexion Admin</h1>
            <p class="text-gray-400">Accédez au dashboard de gestion</p>
        </div>
        
        <?php if ($error): ?>
        <div class="bg-red-900 border border-red-700 text-red-300 px-4 py-3 rounded mb-4">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-white font-medium mb-2">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" 
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors" 
                       required
                       autocomplete="username">
            </div>
            
            <div>
                <label for="password" class="block text-white font-medium mb-2">Mot de passe</label>
                <input type="password" id="password" name="password" 
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors" 
                       required
                       autocomplete="current-password">
            </div>
            
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                Se connecter
            </button>
        </form>
        
        <div class="mt-6 p-4 bg-gray-700 rounded-lg">
            <p class="text-gray-400 text-sm text-center">
                <strong>Identifiants de test :</strong><br>
                Nom d'utilisateur: <code>admin</code><br>
                Mot de passe: <code>admin123</code>
            </p>
        </div>
    </div>
</body>
</html>