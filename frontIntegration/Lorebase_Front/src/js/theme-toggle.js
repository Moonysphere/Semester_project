// src/js/theme-toggle.js

class ThemeToggle {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.button = null;
        this.init();
    }

    init() {
        // Applique le thème sauvegardé IMMÉDIATEMENT
        this.applyTheme(this.theme);

        // Attend que le DOM soit prêt pour créer le bouton
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.createButton();
                this.setupEventListeners();
            });
        } else {
            // DOM déjà prêt
            this.createButton();
            this.setupEventListeners();
        }
    }

    createButton() {
        // Vérifie que le bouton n'existe pas déjà
        if (document.getElementById('themeToggle')) {
            console.warn('Theme toggle already exists');
            return;
        }

        // Crée le bouton
        this.button = document.createElement('button');
        this.button.classList.add('theme-toggle');
        this.button.id = 'themeToggle';
        this.button.setAttribute('aria-label', 'Changer le thème');
        this.button.setAttribute('data-tooltip', this.theme === 'dark' ? 'Mode clair' : 'Mode sombre');

        // Crée l'icône
        const icon = document.createElement('span');
        icon.classList.add('theme-toggle__icon');
        icon.textContent = this.theme === 'dark' ? '☀️' : '🌙';

        this.button.appendChild(icon);
        document.body.appendChild(this.button);

        console.log('✅ Theme toggle button created');
    }

    setupEventListeners() {
        if (this.button) {
            this.button.addEventListener('click', () => this.toggleTheme());
            console.log('✅ Theme toggle listener attached');
        }
    }

    toggleTheme() {
        // Ajoute animation de rotation
        this.button.classList.add('rotating');

        // Change le thème
        this.theme = this.theme === 'light' ? 'dark' : 'light';
        this.applyTheme(this.theme);
        localStorage.setItem('theme', this.theme);

        // Met à jour l'icône et le tooltip
        const icon = this.button.querySelector('.theme-toggle__icon');
        if (icon) {
            icon.textContent = this.theme === 'dark' ? '☀️' : '🌙';
        }
        this.button.setAttribute('data-tooltip', this.theme === 'dark' ? 'Mode clair' : 'Mode sombre');

        // Retire l'animation après 600ms
        setTimeout(() => {
            this.button.classList.remove('rotating');
        }, 600);

        console.log(`Theme switched to: ${this.theme}`);
    }

    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
    }
}

// Auto-initialisation IMMÉDIATE
new ThemeToggle();

export default ThemeToggle;