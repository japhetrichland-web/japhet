<?php
include 'includes/config.php';
?>
<?php include 'includes/header.php'; ?>

<main class="pt-24">
    <!-- Hero Section -->
    <section class="py-16 bg-gradient-to-br from-primary to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">À propos de moi</h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Passionné par la technologie et le design, je mets mes compétences au service de vos projets digitaux.
                </p>
            </div>
        </div>
    </section>

    <!-- Présentation Chronologique -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Informations personnelles -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-800 rounded-lg p-6 sticky top-24">
                        <div class="text-center mb-6">
                            <div class="w-32 h-32 bg-gradient-to-br from-secondary to-purple-900 rounded-full mx-auto mb-4 flex items-center justify-center text-white">
                                Initiales JB
                            </div>
                            <h2 class="text-2xl font-bold text-white">Japhet Bondoumbou</h2>
                            <p class="text-secondary font-medium">Développeur & Designer</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-white font-semibold mb-2">Contact</h3>
                                <p class="text-gray-400">Email: japhet@example.com</p>
                                <p class="text-gray-400">Tél: +XX XXX XXX XXX</p>
                            </div>
                            
                            <div>
                                <h3 class="text-white font-semibold mb-2">Langues</h3>
                                <p class="text-gray-400">Français (Courant)</p>
                                <p class="text-gray-400">Lingala (Maternel)</p>
                            </div>
                            
                            <div>
                                <h3 class="text-white font-semibold mb-2">Centres d'intérêt</h3>
                                <p class="text-gray-400">Technologie</p>
                                <p class="text-gray-400">Programmation</p>
                                <p class="text-gray-400">Design Graphique</p>
                                <p class="text-gray-400">Infographie</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Chronologie -->
                <div class="lg:col-span-2">
                    <div class="space-y-8">
                        <!-- Éducation -->
                        <div class="animate-fade-in">
                            <h2 class="text-3xl font-bold text-white mb-6">Éducation</h2>
                            
                            <div class="space-y-6">
                                <div class="bg-gray-800 rounded-lg p-6 border-l-4 border-secondary">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-xl font-semibold text-white">Licence en Programmation</h3>
                                        <span class="text-secondary font-medium">En cours</span>
                                    </div>
                                    <p class="text-gray-400 mb-2">Formation approfondie en développement logiciel et web</p>
                                    <p class="text-gray-500 text-sm">Spécialisation en technologies modernes</p>
                                </div>
                                
                                <div class="bg-gray-800 rounded-lg p-6 border-l-4 border-secondary">
                                    <h3 class="text-xl font-semibold text-white mb-2">Baccalauréat D</h3>
                                    <p class="text-gray-400">Diplôme de fin d'études secondaires</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Expérience -->
                        <div class="animate-fade-in" style="animation-delay: 0.2s">
                            <h2 class="text-3xl font-bold text-white mb-6">Expérience Professionnelle</h2>
                            
                            <div class="bg-gray-800 rounded-lg p-6 border-l-4 border-secondary">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-semibold text-white">Assistant du Directeur</h3>
                                    <span class="text-secondary font-medium">Août - Oct 2024</span>
                                </div>
                                <p class="text-gray-400 mb-2">Marché Central d'Oyo</p>
                                <ul class="text-gray-400 list-disc list-inside space-y-1">
                                    <li>Gestion administrative et organisationnelle</li>
                                    <li>Coordination des activités du marché</li>
                                    <li>Support à la direction</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Objectifs -->
                        <div class="animate-fade-in" style="animation-delay: 0.4s">
                            <h2 class="text-3xl font-bold text-white mb-6">Objectifs & Vision</h2>
                            
                            <div class="bg-gray-800 rounded-lg p-6">
                                <p class="text-gray-400 mb-4">
                                    Mon objectif est de créer des solutions digitales innovantes qui allient performance technique et design remarquable. 
                                    Je souhaite contribuer à des projets ambitieux tout en continuant à développer mes compétences dans les technologies émergentes.
                                </p>
                                <p class="text-gray-400">
                                    Passionné par l'apprentissage continu, je me tiens constamment informé des dernières tendances en développement 
                                    et design pour offrir des solutions modernes et efficaces.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>