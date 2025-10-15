<?php
include 'includes/config.php';
include 'includes/functions.php';

$skills = get_skills();
$skills_by_category = [];

foreach ($skills as $skill) {
    $category = $skill['category'];
    if (!isset($skills_by_category[$category])) {
        $skills_by_category[$category] = [];
    }
    $skills_by_category[$category][] = $skill;
}
?>
<?php include 'includes/header.php'; ?>

<main class="pt-24">
    <!-- Hero Section -->
    <section class="py-16 bg-gradient-to-br from-primary to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Mes Compétences</h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Un ensemble complet de compétences techniques et créatives pour répondre à tous vos besoins digitaux.
                </p>
            </div>
        </div>
    </section>

    <!-- Compétences par catégorie -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php foreach ($skills_by_category as $category => $category_skills): ?>
            <div class="mb-16 animate-fade-in">
                <h2 class="text-3xl font-bold text-white mb-8 text-center"><?php echo $category; ?></h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($category_skills as $skill): ?>
                    <div class="bg-gray-800 rounded-lg p-6 text-center hover:bg-gray-750 transition-colors group">
                        <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <span class="text-white font-bold text-lg"><?php echo substr($skill['name'], 0, 2); ?></span>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2"><?php echo $skill['name']; ?></h3>
                        <div class="w-full bg-gray-700 rounded-full h-2">
                            <div class="bg-secondary h-2 rounded-full" style="width: <?php echo $skill['level']; ?>%"></div>
                        </div>
                        <p class="text-gray-400 text-sm mt-2">Niveau: <?php echo $skill['level']; ?>%</p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Statistiques -->
    <section class="py-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="animate-fade-in">
                    <div class="text-4xl md:text-5xl font-bold text-secondary mb-2">50+</div>
                    <div class="text-xl text-gray-300">Projets Réalisés</div>
                </div>
                <div class="animate-fade-in" style="animation-delay: 0.2s">
                    <div class="text-4xl md:text-5xl font-bold text-secondary mb-2">15+</div>
                    <div class="text-xl text-gray-300">Technologies Maîtrisées</div>
                </div>
                <div class="animate-fade-in" style="animation-delay: 0.4s">
                    <div class="text-4xl md:text-5xl font-bold text-secondary mb-2">100%</div>
                    <div class="text-xl text-gray-300">Satisfaction Client</div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>