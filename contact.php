<?php
include 'includes/config.php';
include 'includes/functions.php';

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = sanitize_input($_POST['nom'] ?? '');
    $prenom = sanitize_input($_POST['prenom'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $telephone = sanitize_input($_POST['telephone'] ?? '');
    $type_projet = sanitize_input($_POST['type_projet'] ?? '');
    $budget = sanitize_input($_POST['budget'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');
    
    // Validation
    if (empty($nom)) $errors[] = "Le nom est requis";
    if (empty($prenom)) $errors[] = "Le prénom est requis";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Un email valide est requis";
    if (empty($telephone)) $errors[] = "Le téléphone est requis";
    if (empty($type_projet)) $errors[] = "Le type de projet est requis";
    if (empty($budget)) $errors[] = "Le budget est requis";
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO devis_requests (nom, prenom, email, telephone, type_projet, budget, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $email, $telephone, $type_projet, $budget, $message]);
            
            // Envoi d'email (simulé - à configurer avec un vrai serveur SMTP)
            $to = "japhetbondoumbou9@gmail.com";
            $subject = "Nouvelle demande de devis - " . $prenom . " " . $nom;
            $email_message = "
            Nouvelle demande de devis reçue :
            
            Nom: $nom
            Prénom: $prenom
            Email: $email
            Téléphone: $telephone
            Type de projet: $type_projet
            Budget: $budget
            Message: $message
            ";
            
            mail($to, $subject, $email_message); // Décommenter pour un vrai envoi
            
            $success = true;
            
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'enregistrement: " . $e->getMessage();
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<main class="pt-24">
    <!-- Hero Section -->
    <section class="py-16 bg-gradient-to-br from-primary to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Contact</h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Discutons de votre projet et obtenez un devis personnalisé.
                </p>
            </div>
        </div>
    </section>

    <!-- Formulaire de contact -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if ($success): ?>
            <div class="bg-green-900 border border-green-700 text-green-300 px-6 py-4 rounded-lg mb-8 animate-fade-in">
                <p class="font-semibold">Votre demande a été envoyée avec succès !</p>
                <p class="mt-2">Je vous recontacte dans les plus brefs délais.</p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
            <div class="bg-red-900 border border-red-700 text-red-300 px-6 py-4 rounded-lg mb-8 animate-fade-in">
                <p class="font-semibold">Erreurs :</p>
                <ul class="list-disc list-inside mt-2">
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <div class="bg-gray-800 rounded-lg p-8 animate-fade-in">
                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-white font-medium mb-2">Nom *</label>
                            <input type="text" id="nom" name="nom" value="<?php echo $_POST['nom'] ?? ''; ?>" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-secondary transition-colors" required>
                        </div>
                        <div>
                            <label for="prenom" class="block text-white font-medium mb-2">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" value="<?php echo $_POST['prenom'] ?? ''; ?>" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-secondary transition-colors" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-white font-medium mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-secondary transition-colors" required>
                        </div>
                        <div>
                            <label for="telephone" class="block text-white font-medium mb-2">Téléphone *</label>
                            <input type="tel" id="telephone" name="telephone" value="<?php echo $_POST['telephone'] ?? ''; ?>" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-secondary transition-colors" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="type_projet" class="block text-white font-medium mb-2">Type de projet *</label>
                            <select id="type_projet" name="type_projet" 
                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-secondary transition-colors" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="site_web" <?php echo ($_POST['type_projet'] ?? '') === 'site_web' ? 'selected' : ''; ?>>Site Web</option>
                                <option value="application" <?php echo ($_POST['type_projet'] ?? '') === 'application' ? 'selected' : ''; ?>>Application</option>
                                <option value="design" <?php echo ($_POST['type_projet'] ?? '') === 'design' ? 'selected' : ''; ?>>Design Graphique</option>
                                <option value="infographie" <?php echo ($_POST['type_projet'] ?? '') === 'infographie' ? 'selected' : ''; ?>>Infographie</option>
                            </select>
                        </div>
                        <div>
                            <label for="budget" class="block text-white font-medium mb-2">Budget *</label>
                            <select id="budget" name="budget" 
                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-secondary transition-colors" required>
                                <option value="">Sélectionnez un budget</option>
                                <option value="50000" <?php echo ($_POST['budget'] ?? '') === '50000' ? 'selected' : ''; ?>>50 000 - 100 000 FCFA</option>
                                <option value="100000" <?php echo ($_POST['budget'] ?? '') === '100000' ? 'selected' : ''; ?>>100 000 - 250 000 FCFA</option>
                                <option value="250000" <?php echo ($_POST['budget'] ?? '') === '250000' ? 'selected' : ''; ?>>250 000 - 500 000 FCFA</option>
                                <option value="500000" <?php echo ($_POST['budget'] ?? '') === '500000' ? 'selected' : ''; ?>>500 000 - 1 000 000 FCFA</option>
                                <option value="1000000" <?php echo ($_POST['budget'] ?? '') === '1000000' ? 'selected' : ''; ?>>Plus d'1 000 000 FCFA</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-white font-medium mb-2">Message</label>
                        <textarea id="message" name="message" rows="6" 
                                  class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-secondary transition-colors"><?php echo $_POST['message'] ?? ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-secondary hover:bg-purple-700 text-white font-semibold py-4 px-8 rounded-lg transition-colors">
                        Envoyer la demande de devis
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>