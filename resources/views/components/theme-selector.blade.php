<div class="dropdown dropdown-end" x-data="{ open: false }">
    <!-- Bouton du sélecteur -->
    <label tabindex="0" class="btn btn-ghost btn-circle" @click="open = !open">
        <i class="fas fa-palette text-2xl" style="color: #036f15;"></i>
    </label>
    
    <!-- Menu déroulant -->
    <ul tabindex="0" class="dropdown-content menu p-2 shadow-2xl bg-base-100 rounded-box w-72 z-50" 
        x-show="open" 
        @click.away="open = false"
        x-transition>
        
        <li class="menu-title">
            <span class="text-base-content font-bold">🎨 Choisissez un thème</span>
        </li>
        
        <div class="divider my-1"></div>
        
        <!-- Thème Forêt -->
        <li class="p-2">
            <button onclick="setTheme('foret')" class="btn btn-sm btn-block justify-start gap-3 h-auto py-3" 
                    style="background: #036f15; color: white; border: none;">
                <i class="fas fa-leaf fa-lg"></i>
                <div class="text-left">
                    <span class="font-bold block">🌳 Forêt</span>
                    <small class="opacity-80 block">Thème par défaut</small>
                </div>
            </button>
        </li>
        
        <!-- Thème Lecture facile -->
        <li class="p-2">
            <button onclick="setTheme('easy-read')" class="btn btn-sm btn-block justify-start gap-3 h-auto py-3"
                    style="background: #0066cc; color: white; border: none;">
                <i class="fas fa-book-open fa-lg"></i>
                <div class="text-left">
                    <span class="font-bold block">📖 Lecture facile</span>
                    <small class="opacity-80 block">Grands textes, contraste optimisé</small>
                </div>
            </button>
        </li>
        
        <!-- Thème Nuit -->
        <li class="p-2">
            <button onclick="setTheme('night-mode')" class="btn btn-sm btn-block justify-start gap-3 h-auto py-3"
                    style="background: #121212; color: #e0e0e0; border: 1px solid #4caf50;">
                <i class="fas fa-moon fa-lg"></i>
                <div class="text-left">
                    <span class="font-bold block">🌙 Mode nuit</span>
                    <small class="opacity-80 block">Soulage les yeux fatigués</small>
                </div>
            </button>
        </li>
        
        <!-- Thème Contraste élevé -->
        <li class="p-2">
            <button onclick="setTheme('high-contrast')" class="btn btn-sm btn-block justify-start gap-3 h-auto py-3"
                    style="background: #000000; color: #ffff00; border: 2px solid #ffff00;">
                <i class="fas fa-eye fa-lg"></i>
                <div class="text-left">
                    <span class="font-bold block">👁️ Contraste élevé</span>
                    <small class="opacity-80 block">Pour malvoyants</small>
                </div>
            </button>
        </li>
        
        <!-- Thème Doux -->
        <li class="p-2">
            <button onclick="setTheme('soft-theme')" class="btn btn-sm btn-block justify-start gap-3 h-auto py-3"
                    style="background: #81c784; color: #2c3e50; border: none;">
                <i class="fas fa-cloud fa-lg"></i>
                <div class="text-left">
                    <span class="font-bold block">🌸 Doux</span>
                    <small class="opacity-80 block">Tons pastel apaisants</small>
                </div>
            </button>
        </li>
        
        <div class="divider my-1"></div>
        
        <!-- Pied du menu -->
        <li class="p-2">
            <p class="text-xs text-base-content opacity-60 text-center">
                <i class="fas fa-info-circle me-1"></i>
                Le thème est sauvegardé automatiquement
            </p>
        </li>
    </ul>
</div>

<script>
    // Fonction pour changer de thème
    function setTheme(theme) {
        // Sauvegarder dans localStorage
        localStorage.setItem('selectedTheme', theme);
        
        // Appliquer le thème
        document.documentElement.setAttribute('data-theme', theme);
        
        // Animation de confirmation
        showToast('Thème changé avec succès');
        
        // Fermer le dropdown
        Alpine.store('themeDropdown', false);
    }
    
    // Fonction pour afficher une notification
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-primary text-primary-content px-6 py-3 rounded-lg shadow-2xl z-50 animate-fade-in-up flex items-center gap-3';
        toast.innerHTML = `
            <i class="fas fa-check-circle text-2xl"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'fadeOutDown 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Charger le thème sauvegardé au démarrage
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('selectedTheme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        }
        
        // Optionnel : détecter la préférence système
        if (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-theme', 'night-mode');
        }
    });
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeOutDown {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out;
    }
</style>