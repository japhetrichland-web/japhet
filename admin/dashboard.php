<?php
session_start();

// Vérification de l'authentification et timeout de session
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Vérification du timeout de session (8 heures)
$session_timeout = 8 * 60 * 60; // 8 heures en secondes
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $session_timeout)) {
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}

// Régénération de l'ID de session périodiquement pour la sécurité
if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutes
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

include '../includes/config.php';

// Récupération des statistiques
$projects_count = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$skills_count = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$devis_count = $pdo->query("SELECT COUNT(*) FROM devis_requests")->fetchColumn();
$devis_new_count = $pdo->query("SELECT COUNT(*) FROM devis_requests WHERE status = 'new'")->fetchColumn();

// Récupération des demandes récentes
$recent_devis = $pdo->query("SELECT * FROM devis_requests ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Informations de session
$username = $_SESSION['admin_username'] ?? 'Admin';
$login_time = isset($_SESSION['login_time']) ? date('d/m/Y H:i', $_SESSION['login_time']) : 'Inconnu';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Portfolio Japhet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen">
    <!-- Navigation Admin -->
    <nav class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold text-white">Dashboard Admin</h1>
                    <span class="text-gray-400 text-sm">|</span>
                    <span class="text-gray-300 text-sm">Connecté en tant que: <?php echo $username; ?></span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden md:block">
                        <div class="text-gray-300 text-sm">Connexion: <?php echo $login_time; ?></div>
                        <div class="text-gray-400 text-xs">Session active</div>
                    </div>
                    <a href="../index.php" class="text-gray-300 hover:text-white transition-colors bg-gray-700 hover:bg-gray-600 px-3 py-2 rounded-lg">
                        Voir le site
                    </a>
                    <div class="relative group">
                        <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Mon compte
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-lg py-2 hidden group-hover:block z-50">
                            <div class="px-4 py-2 border-b border-gray-700">
                                <p class="text-white text-sm"><?php echo $username; ?></p>
                                <p class="text-gray-400 text-xs">Administrateur</p>
                            </div>
                            <a href="logout.php" class="block px-4 py-2 text-red-400 hover:text-red-300 hover:bg-gray-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Bannière de bienvenue -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-2">Bienvenue, <?php echo $username; ?> !</h2>
                    <p class="text-purple-100">Gérez votre portfolio et suivez les demandes de devis</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800 rounded-lg p-6 hover:bg-gray-750 transition-colors">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-20 text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-white">Projets</h3>
                        <p class="text-3xl font-bold text-blue-400"><?php echo $projects_count; ?></p>
                    </div>
                </div>
                <a href="manage_projects.php" class="text-gray-400 hover:text-white transition-colors text-sm block mt-2">Gérer les projets →</a>
            </div>
            
            <div class="bg-gray-800 rounded-lg p-6 hover:bg-gray-750 transition-colors">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-20 text-green-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-white">Compétences</h3>
                        <p class="text-3xl font-bold text-green-400"><?php echo $skills_count; ?></p>
                    </div>
                </div>
                <a href="manage_skills.php" class="text-gray-400 hover:text-white transition-colors text-sm block mt-2">Gérer les compétences →</a>
            </div>
            
            <div class="bg-gray-800 rounded-lg p-6 hover:bg-gray-750 transition-colors">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500 bg-opacity-20 text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-white">Demandes DV</h3>
                        <p class="text-3xl font-bold text-purple-400"><?php echo $devis_count; ?></p>
                    </div>
                </div>
                <a href="manage_devis.php" class="text-gray-400 hover:text-white transition-colors text-sm block mt-2">Gérer les demandes →</a>
            </div>
            
            <div class="bg-gray-800 rounded-lg p-6 hover:bg-gray-750 transition-colors">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-20 text-yellow-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-white">Nouvelles</h3>
                        <p class="text-3xl font-bold text-yellow-400"><?php echo $devis_new_count; ?></p>
                    </div>
                </div>
                <a href="manage_devis.php?filter=new" class="text-gray-400 hover:text-white transition-colors text-sm block mt-2">Voir les nouvelles →</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Actions rapides -->
            <div class="bg-gray-800 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Actions rapides</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="manage_projects.php?action=add" class="bg-purple-600 hover:bg-purple-700 text-white text-center py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter un projet
                    </a>
                    <a href="manage_skills.php?action=add" class="bg-green-600 hover:bg-green-700 text-white text-center py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter une compétence
                    </a>
                    <a href="manage_projects.php" class="bg-blue-600 hover:bg-blue-700 text-white text-center py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier un projet
                    </a>
                    <a href="manage_skills.php" class="bg-indigo-600 hover:bg-indigo-700 text-white text-center py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier une compétence
                    </a>
                </div>
            </div>

            <!-- Demandes récentes -->
            <div class="bg-gray-800 rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-white">Demandes récentes</h2>
                    <a href="manage_devis.php" class="text-purple-400 hover:text-purple-300 text-sm">Voir tout →</a>
                </div>
                
                <div class="space-y-4">
                    <?php if (empty($recent_devis)): ?>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v1M9 7h6"></path>
                            </svg>
                            <p class="text-gray-400">Aucune demande récente</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_devis as $devis): ?>
                        <div class="bg-gray-700 rounded-lg p-4 hover:bg-gray-650 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold text-white"><?php echo $devis['prenom'] . ' ' . $devis['nom']; ?></h3>
                                    <p class="text-gray-400 text-sm"><?php echo $devis['email']; ?></p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    <?php echo $devis['status'] === 'new' ? 'bg-yellow-500 text-yellow-900' : 
                                           ($devis['status'] === 'contacted' ? 'bg-blue-500 text-blue-900' : 'bg-green-500 text-green-900'); ?>">
                                    <?php echo $devis['status'] === 'new' ? 'Nouveau' : 
                                          ($devis['status'] === 'contacted' ? 'Contacté' : 'Traité'); ?>
                                </span>
                            </div>
                            <p class="text-gray-300 text-sm mb-2">
                                <?php 
                                $type_projet = [
                                    'site_web' => 'Site Web',
                                    'application' => 'Application',
                                    'design' => 'Design Graphique',
                                    'infographie' => 'Infographie'
                                ];
                                echo $type_projet[$devis['type_projet']] . ' - ' . number_format($devis['budget']) . ' FCFA'; 
                                ?>
                            </p>
                            <p class="text-gray-400 text-xs"><?php echo date('d/m/Y H:i', strtotime($devis['created_at'])); ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Session info -->
        <div class="mt-8 bg-gray-800 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Informations de session</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-400">Utilisateur:</span>
                    <span class="text-white ml-2"><?php echo $username; ?></span>
                </div>
                <div>
                    <span class="text-gray-400">Connexion:</span>
                    <span class="text-white ml-2"><?php echo $login_time; ?></span>
                </div>
                <div>
                    <span class="text-gray-400">Durée:</span>
                    <span class="text-white ml-2">
                        <?php 
                        $duration = time() - $_SESSION['login_time'];
                        $hours = floor($duration / 3600);
                        $minutes = floor(($duration % 3600) / 60);
                        echo $hours . 'h ' . $minutes . 'm';
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-logout après inactivité (30 minutes)
        let inactivityTime = function () {
            let time;
            window.onload = resetTimer;
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;
            
            function logout() {
                window.location.href = 'logout.php?inactivity=1';
            }
            
            function resetTimer() {
                clearTimeout(time);
                time = setTimeout(logout, 30 * 60 * 1000); // 30 minutes
            }
        };
        
        inactivityTime();
    </script>
</body>
</html>