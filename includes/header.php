<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japhet Bondoumbou - Développeur & Designer Graphique</title>
    <meta name="description" content="Portfolio de Japhet Bondoumbou, développeur full-stack et designer graphique/infographique">
    <meta name="keywords" content="développeur, designer, infographie, programmation, web, PHP, JavaScript">
    <meta name="author" content="Japhet Bondoumbou Peya Richiland">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#000',
                        secondary: '#7B2CBF',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-primary text-white font-sans">
    <!-- Navigation -->
    <nav class="bg-primary/90 backdrop-blur-sm fixed w-full z-50 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="index.php" class="text-2xl font-bold text-white">
                    Japhet<span class="text-secondary">.</span>
                </a>
                
                <div class="hidden md:flex space-x-8">
                    <a href="index.php" class="text-white hover:text-secondary transition-colors">Accueil</a>
                    <a href="about.php" class="text-white hover:text-secondary transition-colors">À propos</a>
                    <a href="projects.php" class="text-white hover:text-secondary transition-colors">Projets</a>
                    <a href="skills.php" class="text-white hover:text-secondary transition-colors">Compétences</a>
                    <a href="contact.php" class="text-white hover:text-secondary transition-colors">Contact</a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-primary/95 backdrop-blur-sm border-t border-gray-800">
            <div class="px-4 py-6 space-y-4">
                <a href="index.php" class="block text-white hover:text-secondary transition-colors">Accueil</a>
                <a href="about.php" class="block text-white hover:text-secondary transition-colors">À propos</a>
                <a href="projects.php" class="block text-white hover:text-secondary transition-colors">Projets</a>
                <a href="skills.php" class="block text-white hover:text-secondary transition-colors">Compétences</a>
                <a href="contact.php" class="block text-white hover:text-secondary transition-colors">Contact</a>
            </div>
        </div>
    </nav>