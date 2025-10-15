<?php
include 'includes/config.php';
include 'includes/functions.php';

$projects = get_projects();
?>
<?php include 'includes/header.php'; ?>

<main class="pt-24">
    <!-- Hero Section -->
    <section class="py-16 bg-gradient-to-br from-primary to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Mes Projets</h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Découvrez l'ensemble de mes réalisations en développement web, design graphique et infographie.
                </p>
            </div>
        </div>
    </section>

    <!-- Filtres -->
    <section class="py-8 bg-gray-900 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-4 justify-center">
                <button class="filter-btn active bg-secondary text-white px-4 py-2 rounded-lg transition-colors" data-filter="all">
                    Tous les projets
                </button>
                <button class="filter-btn bg-gray-800 text-gray-400 hover:text-white px-4 py-2 rounded-lg transition-colors" data-filter="web">
                    Développement Web
                </button>
                <button class="filter-btn bg-gray-800 text-gray-400 hover:text-white px-4 py-2 rounded-lg transition-colors" data-filter="design">
                    Design Graphique
                </button>
                <button class="filter-btn bg-gray-800 text-gray-400 hover:text-white px-4 py-2 rounded-lg transition-colors" data-filter="infographie">
                    Infographie
                </button>
            </div>
        </div>
    </section>

    <!-- Liste des projets -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($projects as $project): ?>
                <div class="project-item bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-all animate-fade-in" data-category="<?php echo strtolower($project['category']); ?>">
                    <div class="h-48 bg-gradient-to-r from-secondary to-purple-900 flex items-center justify-center text-white">
                        <?php echo $project['image'] ? '<img src="'.$project['image'].'" alt="'.$project['title'].'" class="w-full h-full object-cover">' : 'Image Projet'; ?>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-semibold text-white"><?php echo $project['title']; ?></h3>
                            <span class="bg-secondary text-white text-sm px-2 py-1 rounded"><?php echo $project['category']; ?></span>
                        </div>
                        <p class="text-gray-400 mb-4"><?php echo $project['description']; ?></p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php
                            $technologies = explode(',', $project['technologies']);
                            foreach ($technologies as $tech):
                            ?>
                            <span class="bg-gray-700 text-gray-300 text-xs px-2 py-1 rounded"><?php echo trim($tech); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($project['link']): ?>
                        <a href="<?php echo $project['link']; ?>" target="_blank" class="text-secondary hover:text-purple-400 transition-colors font-medium">
                            Voir le projet →
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (empty($projects)): ?>
            <div class="text-center py-12">
                <p class="text-gray-400 text-xl">Aucun projet à afficher pour le moment.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectItems = document.querySelectorAll('.project-item');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active', 'bg-secondary', 'text-white'));
            filterBtns.forEach(b => b.classList.add('bg-gray-800', 'text-gray-400'));
            
            // Add active class to clicked button
            this.classList.add('active', 'bg-secondary', 'text-white');
            this.classList.remove('bg-gray-800', 'text-gray-400');
            
            const filter = this.getAttribute('data-filter');
            
            projectItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, 100);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>