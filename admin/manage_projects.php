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
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $technologies = $_POST['technologies'] ?? '';
    $link = $_POST['link'] ?? '';
    $image = $_POST['image'] ?? '';
    
    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO projects (title, description, category, technologies, link, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $category, $technologies, $link, $image]);
        header('Location: manage_projects.php?success=added');
        exit;
    } elseif ($action === 'edit' && $id) {
        $stmt = $pdo->prepare("UPDATE projects SET title=?, description=?, category=?, technologies=?, link=?, image=? WHERE id=?");
        $stmt->execute([$title, $description, $category, $technologies, $link, $image, $id]);
        header('Location: manage_projects.php?success=updated');
        exit;
    }
}

// Suppression
if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id=?");
    $stmt->execute([$id]);
    header('Location: manage_projects.php?success=deleted');
    exit;
}

// Récupération des données pour l'édition
$project = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id=?");
    $stmt->execute([$id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupération de tous les projets
$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Projets - Portfolio Japhet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen">
    <nav class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-xl font-bold text-white">
                    <?php echo $action === 'add' ? 'Ajouter un projet' : ($action === 'edit' ? 'Modifier le projet' : 'Gestion des Projets'); ?>
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
                case 'added': echo 'Projet ajouté avec succès!'; break;
                case 'updated': echo 'Projet modifié avec succès!'; break;
                case 'deleted': echo 'Projet supprimé avec succès!'; break;
            }
            ?>
        </div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
        <!-- Liste des projets -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Liste des projets</h2>
            <a href="?action=add" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                Ajouter un projet
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <table class="w-full text-white">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">Titre</th>
                        <th class="px-6 py-3 text-left">Catégorie</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach ($projects as $proj): ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo $proj['title']; ?></td>
                        <td class="px-6 py-4">
                            <span class="bg-purple-600 text-white text-sm px-2 py-1 rounded"><?php echo $proj['category']; ?></span>
                        </td>
                        <td class="px-6 py-4"><?php echo date('d/m/Y', strtotime($proj['created_at'])); ?></td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="?action=edit&id=<?php echo $proj['id']; ?>" class="text-blue-400 hover:text-blue-300 transition-colors">Modifier</a>
                            <a href="?action=delete&id=<?php echo $proj['id']; ?>" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet?')"
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
                    <label for="title" class="block text-white font-medium mb-2">Titre *</label>
                    <input type="text" id="title" name="title" value="<?php echo $project['title'] ?? ''; ?>" 
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors" required>
                </div>

                <div>
                    <label for="description" class="block text-white font-medium mb-2">Description *</label>
                    <textarea id="description" name="description" rows="4" 
                              class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors" required><?php echo $project['description'] ?? ''; ?></textarea>
                </div>

                <div>
                    <label for="category" class="block text-white font-medium mb-2">Catégorie *</label>
                    <select id="category" name="category" 
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors" required>
                        <option value="">Sélectionnez une catégorie</option>
                        <option value="Web" <?php echo ($project['category'] ?? '') === 'Web' ? 'selected' : ''; ?>>Développement Web</option>
                        <option value="Design" <?php echo ($project['category'] ?? '') === 'Design' ? 'selected' : ''; ?>>Design Graphique</option>
                        <option value="Infographie" <?php echo ($project['category'] ?? '') === 'Infographie' ? 'selected' : ''; ?>>Infographie</option>
                    </select>
                </div>

                <div>
                    <label for="technologies" class="block text-white font-medium mb-2">Technologies (séparées par des virgules)</label>
                    <input type="text" id="technologies" name="technologies" value="<?php echo $project['technologies'] ?? ''; ?>" 
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors">
                </div>

                <div>
                    <label for="link" class="block text-white font-medium mb-2">Lien</label>
                    <input type="url" id="link" name="link" value="<?php echo $project['link'] ?? ''; ?>" 
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors">
                </div>

                <div>
                    <label for="image" class="block text-white font-medium mb-2">Image (URL)</label>
                    <input type="url" id="image" name="image" value="<?php echo $project['image'] ?? ''; ?>" 
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-purple-500 transition-colors">
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                        <?php echo $action === 'add' ? 'Ajouter' : 'Modifier'; ?> le projet
                    </button>
                    <a href="manage_projects.php" class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>