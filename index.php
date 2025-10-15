<?php
include 'includes/config.php';
include 'includes/functions.php';
?>
<?php include 'includes/header.php'; ?>

<main>
    <!-- Hero Section -->
    <section class="pt-24 pb-16 md:pt-32 md:pb-24 bg-gradient-to-br from-primary to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-fade-in">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                        Développeur <span class="text-secondary">Full-Stack</span> & Designer <span class="text-secondary">Graphique</span>
                    </h1>
                    <p class="text-xl text-gray-300 mb-8">
                        Je crée des expériences digitales innovantes qui marient code et design pour des solutions uniques et performantes.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="projects.php" class="bg-secondary hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors text-center">
                            Découvrir mes projets
                        </a>
                        <a href="contact.php" class="border border-secondary text-secondary hover:bg-secondary hover:text-white font-semibold py-3 px-8 rounded-lg transition-colors text-center">
                            Me contacter
                        </a>
                    </div>
                </div>
                <div class="animate-slide-up flex justify-center">
                    <div class="relative">
                        <div class="w-64 h-64 md:w-80 md:h-80 bg-gradient-to-br from-secondary to-purple-900 rounded-full flex items-center justify-center">
                            <!-- Placeholder pour photo de profil -->
                            <div class="w-60 h-60 md:w-76 md:h-76 bg-gray-800 rounded-full flex items-center justify-center text-white text-lg">
                                <img src="assets/images/profil.jpg" alt="Photo de Profil" class="w-full h-full object-cover rounded-full brightness-90 contrast-110 saturate-150 hover:brightness-100 hover:contrast-125 transition-all duration-500">
                            </div>
                        </div>
                        <div class="absolute -bottom-4 -right-4 bg-primary border border-secondary rounded-lg p-4 shadow-lg">
                            <p class="text-sm font-semibold">Disponible pour<br>vos projets</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projets Récents -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Projets Récents</h2>
                <p class="text-xl text-gray-400">Découvrez quelques-unes de mes réalisations</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $projects = get_projects(3);
                foreach ($projects as $project):
                ?>
                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow animate-fade-in">
                    <div class="h-48 bg-gradient-to-r from-secondary to-purple-900 flex items-center justify-center text-white">
                        <?php echo $project['image'] ? '<img src="'.$project['image'].'" alt="'.$project['title'].'" class="w-full h-full object-cover">' : 'Image Projet'; ?>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-white mb-2"><?php echo $project['title']; ?></h3>
                        <p class="text-gray-400 mb-4"><?php echo substr($project['description'], 0, 100); ?>...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary font-medium"><?php echo $project['category']; ?></span>
                            <a href="projects.php#project-<?php echo $project['id']; ?>" class="text-white hover:text-secondary transition-colors">Voir plus →</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-12">
                <a href="projects.php" class="inline-block bg-secondary hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                    Voir tous les projets
                </a>
            </div>
        </div>
    </section>

    <!-- Compétences Principales -->
    <section class="py-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Compétences Principales</h2>
                <p class="text-xl text-gray-400">Expertise technique et créative</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-800 rounded-lg p-6 text-center hover:bg-gray-750 transition-colors animate-fade-in">
                    <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Développement Web</h3>
                    <p class="text-gray-400">Création d'applications web modernes et performantes avec les dernières technologies.</p>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6 text-center hover:bg-gray-750 transition-colors animate-fade-in" style="animation-delay: 0.2s">
                    <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17v.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Design Graphique</h3>
                    <p class="text-gray-400">Conception d'identités visuelles percutantes et de supports de communication.</p>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6 text-center hover:bg-gray-750 transition-colors animate-fade-in" style="animation-delay: 0.4s">
                    <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Infographie</h3>
                    <p class="text-gray-400">Création de supports visuels impactants pour vos campagnes de communication.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>