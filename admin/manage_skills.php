<?php
session_start();

// Vérification de l'authentification
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Vérification du timeout de session (8 heures)
$session_timeout = 8 * 60 * 60;
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $session_timeout)) {
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

include '../includes/config.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $level = $_POST['level'] ?? '';
    
    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO skills (name, category, level) VALUES (?, ?, ?)");
        $stmt->execute([$name, $category, $level]);
        header('Location: manage_skills.php?success=added');
        exit;
    } elseif ($action === 'edit' && $id) {
        $stmt = $pdo->prepare("UPDATE skills SET name=?, category=?, level=? WHERE id=?");
        $stmt->execute([$name, $category, $level, $id]);
        header('Location: manage_skills.php?success=updated');
        exit;
    }
}

// Suppression
if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM skills WHERE id=?");
    $stmt->execute([$id]);
    header('Location: manage_skills.php?success=deleted');
    exit;
}

// Récupération des données pour l'édition
$skill = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM skills WHERE id=?");
    $stmt->execute([$id]);
    $skill = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupération de toutes les compétences
$skills = $pdo->query("SELECT * FROM skills ORDER BY category, name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Compétences - Portfolio Japhet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen">
    <nav class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-xl font-bold text-white">
                    <?php echo $action === 'add' ? 'Ajouter une compétence' : ($action === 'edit' ? 'Modifier la compétence' : 'Gestion des Compétences'); ?>
                </h1>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-gray-300 hover:text-white transition-colors">Dashboard</a>
                    <a href="../index.php" class="text-gray-300 hover:text-white transition-colors">Voir le site</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-900 border border-green-700 text-green-300 px-6 py-4 rounded-lg mb-8">
            <?php
            switch ($_GET['success']) {
                case 'added': echo 'Compétence ajoutée avec succès!'; break;
                case 'updated': echo 'Compétence modifiée avec succès!'; break;
                case 'deleted': echo 'Compétence supprimée avec succès!'; break;
            }
            ?>
        </div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
        <!-- Liste des compétences -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Liste des compétences</h2>
            <a href="?action=add" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                Ajouter une compétence
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <table class="w-full text-white">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">Nom</th>
                        <th class="px-6 py-3 text-left">Catégorie</th>
                        <th class="px-6 py-3 text-left">Niveau</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach ($skills as $sk): ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo $sk['name']; ?></td>
                        <td class="px-6 py-4">
                            <span class="bg-purple-600 text-white text-sm px-2 py-1 rounded"><?php echo $sk['category']; ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-32 bg-gray-700 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: <?php echo $sk['level']; ?>%"></div>
                            </div>
                            <span class="text-sm text-gray-400"><?php echo $sk['level']; ?>%</span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="?action=edit&id=<?php echo $sk['id']; ?>" class="text-blue-400 hover:text-blue-300 transition-colors">Modifier</a>
                            <a href="?action=delete&id=<?php echo $sk['id']; ?>" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette compétence?')"
                               class="text-red-400 hover:text-red-300 transition-colors">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php else: ?>
        <!-- Formulaire d'ajout/modification -->
        <div class="bg-gray-800 rounded-lg p-6 max-w-2xl mx-auto">
            <form method="POST" class="space-y-6">
                <div>
                    <label for="name" class="block text-white font-medium mb-2">Nom *</label>
                    <input type="text" id="name" name="name" value="<?php echo $skill['name'] ?? ''; ?>" 
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors" required>
                </div>

                <div>
                    <label for="category" class="block text-white font-medium mb-2">Catégorie *</label>
                    <select id="category" name="category" 
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors" required>
                        <option value="">Sélectionnez une catégorie</option>
                        <option value="Programmation" <?php echo ($skill['category'] ?? '') === 'Programmation' ? 'selected' : ''; ?>>Programmation</option>
                        <option value="Outils" <?php echo ($skill['category'] ?? '') === 'Outils' ? 'selected' : ''; ?>>Outils</option>
                        <option value="Design/Infographie" <?php echo ($skill['category'] ?? '') === 'Design/Infographie' ? 'selected' : ''; ?>>Design/Infographie</option>
                    </select>
                </div>

                <div>
                    <label for="level" class="block text-white font-medium mb-2">Niveau (%) *</label>
                    <input type="range" id="level" name="level" min="0" max="100" value="<?php echo $skill['level'] ?? '50'; ?>" 
                           class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-purple-500"
                           oninput="document.getElementById('levelValue').textContent = this.value + '%'">
                    <div class="text-center text-gray-400 mt-2">
                        <span id="levelValue"><?php echo $skill['level'] ?? '50'; ?>%</span>
                    </div>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                        <?php echo $action === 'add' ? 'Ajouter' : 'Modifier'; ?> la compétence
                    </button>
                    <a href="manage_skills.php" class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('level').addEventListener('input', function() {
            document.getElementById('levelValue').textContent = this.value + '%';
        });
    </script>
</body>
</html>