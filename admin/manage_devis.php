<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

include '../includes/config.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$filter = $_GET['filter'] ?? 'all';

// Traitement des actions
if ($action === 'update_status' && $id) {
    $new_status = $_POST['status'] ?? '';
    if (in_array($new_status, ['new', 'contacted', 'completed'])) {
        $stmt = $pdo->prepare("UPDATE devis_requests SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $id]);
        header('Location: manage_devis.php?success=updated');
        exit;
    }
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM devis_requests WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: manage_devis.php?success=deleted');
    exit;
}

// Récupération des demandes avec filtre
$where = '';
$params = [];

if ($filter === 'new') {
    $where = 'WHERE status = "new"';
} elseif ($filter === 'contacted') {
    $where = 'WHERE status = "contacted"';
} elseif ($filter === 'completed') {
    $where = 'WHERE status = "completed"';
}

$sql = "SELECT * FROM devis_requests $where ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$devis_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistiques pour les filtres
$total_count = $pdo->query("SELECT COUNT(*) FROM devis_requests")->fetchColumn();
$new_count = $pdo->query("SELECT COUNT(*) FROM devis_requests WHERE status = 'new'")->fetchColumn();
$contacted_count = $pdo->query("SELECT COUNT(*) FROM devis_requests WHERE status = 'contacted'")->fetchColumn();
$completed_count = $pdo->query("SELECT COUNT(*) FROM devis_requests WHERE status = 'completed'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Demandes - Portfolio Japhet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen">
    <nav class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-xl font-bold text-white">Gestion des Demandes de Devis</h1>
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
                case 'updated': echo 'Statut mis à jour avec succès!'; break;
                case 'deleted': echo 'Demande supprimée avec succès!'; break;
            }
            ?>
        </div>
        <?php endif; ?>

        <!-- Filtres -->
        <div class="bg-gray-800 rounded-lg p-6 mb-8">
            <h2 class="text-lg font-semibold text-white mb-4">Filtrer les demandes</h2>
            <div class="flex flex-wrap gap-4">
                <a href="?filter=all" class="px-4 py-2 rounded-lg transition-colors <?php echo $filter === 'all' ? 'bg-purple-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'; ?>">
                    Toutes (<?php echo $total_count; ?>)
                </a>
                <a href="?filter=new" class="px-4 py-2 rounded-lg transition-colors <?php echo $filter === 'new' ? 'bg-yellow-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'; ?>">
                    Nouvelles (<?php echo $new_count; ?>)
                </a>
                <a href="?filter=contacted" class="px-4 py-2 rounded-lg transition-colors <?php echo $filter === 'contacted' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'; ?>">
                    Contactées (<?php echo $contacted_count; ?>)
                </a>
                <a href="?filter=completed" class="px-4 py-2 rounded-lg transition-colors <?php echo $filter === 'completed' ? 'bg-green-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'; ?>">
                    Traitées (<?php echo $completed_count; ?>)
                </a>
            </div>
        </div>

        <!-- Liste des demandes -->
        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <?php if (empty($devis_requests)): ?>
                <div class="text-center py-12">
                    <p class="text-gray-400 text-xl">Aucune demande trouvée.</p>
                </div>
            <?php else: ?>
                <table class="w-full text-white">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left">Client</th>
                            <th class="px-6 py-3 text-left">Contact</th>
                            <th class="px-6 py-3 text-left">Projet</th>
                            <th class="px-6 py-3 text-left">Budget</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Statut</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php foreach ($devis_requests as $devis): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-semibold"><?php echo $devis['prenom'] . ' ' . $devis['nom']; ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm"><?php echo $devis['email']; ?></div>
                                <div class="text-sm text-gray-400"><?php echo $devis['telephone']; ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="capitalize"><?php echo str_replace('_', ' ', $devis['type_projet']); ?></div>
                                <?php if ($devis['message']): ?>
                                <div class="text-sm text-gray-400 truncate max-w-xs"><?php echo $devis['message']; ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold"><?php echo number_format($devis['budget']); ?> FCFA</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm"><?php echo date('d/m/Y', strtotime($devis['created_at'])); ?></div>
                                <div class="text-xs text-gray-400"><?php echo date('H:i', strtotime($devis['created_at'])); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="?action=update_status&id=<?php echo $devis['id']; ?>" class="inline">
                                    <select name="status" onchange="this.form.submit()" 
                                            class="bg-gray-700 border border-gray-600 rounded px-2 py-1 text-white text-sm focus:outline-none focus:border-purple-500">
                                        <option value="new" <?php echo $devis['status'] === 'new' ? 'selected' : ''; ?>>Nouveau</option>
                                        <option value="contacted" <?php echo $devis['status'] === 'contacted' ? 'selected' : ''; ?>>Contacté</option>
                                        <option value="completed" <?php echo $devis['status'] === 'completed' ? 'selected' : ''; ?>>Traité</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button onclick="showDetails(<?php echo htmlspecialchars(json_encode($devis)); ?>)" 
                                        class="text-blue-400 hover:text-blue-300 transition-colors">
                                    Détails
                                </button>
                                <a href="?action=delete&id=<?php echo $devis['id']; ?>" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande?')"
                                   class="text-red-400 hover:text-red-300 transition-colors">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal de détails -->
    <div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-white">Détails de la demande</h3>
                <button onclick="hideDetails()" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="modalContent" class="space-y-4">
                <!-- Le contenu sera rempli par JavaScript -->
            </div>
            
            <div class="mt-6 flex justify-end space-x-4">
                <button onclick="hideDetails()" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <script>
        function showDetails(devis) {
            const typeProjetMap = {
                'site_web': 'Site Web',
                'application': 'Application',
                'design': 'Design Graphique',
                'infographie': 'Infographie'
            };
            
            const statusMap = {
                'new': 'Nouveau',
                'contacted': 'Contacté',
                'completed': 'Traité'
            };
            
            const content = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-gray-400 text-sm">Nom complet</h4>
                        <p class="text-white">${devis.prenom} ${devis.nom}</p>
                    </div>
                    <div>
                        <h4 class="text-gray-400 text-sm">Email</h4>
                        <p class="text-white">${devis.email}</p>
                    </div>
                    <div>
                        <h4 class="text-gray-400 text-sm">Téléphone</h4>
                        <p class="text-white">${devis.telephone}</p>
                    </div>
                    <div>
                        <h4 class="text-gray-400 text-sm">Type de projet</h4>
                        <p class="text-white">${typeProjetMap[devis.type_projet]}</p>
                    </div>
                    <div>
                        <h4 class="text-gray-400 text-sm">Budget</h4>
                        <p class="text-white">${Number(devis.budget).toLocaleString()} FCFA</p>
                    </div>
                    <div>
                        <h4 class="text-gray-400 text-sm">Statut</h4>
                        <p class="text-white">${statusMap[devis.status]}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-gray-400 text-sm">Message</h4>
                        <p class="text-white bg-gray-700 p-3 rounded-lg">${devis.message || 'Aucun message'}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-gray-400 text-sm">Date de demande</h4>
                        <p class="text-white">${new Date(devis.created_at).toLocaleString('fr-FR')}</p>
                    </div>
                </div>
            `;
            
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('detailsModal').classList.remove('hidden');
            document.getElementById('detailsModal').classList.add('flex');
        }
        
        function hideDetails() {
            document.getElementById('detailsModal').classList.add('hidden');
            document.getElementById('detailsModal').classList.remove('flex');
        }
        
        // Fermer la modal avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDetails();
            }
        });
    </script>
</body>
</html>